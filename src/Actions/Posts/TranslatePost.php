<?php

namespace NextDeveloper\Blogs\Actions\Posts;

use App\Models\User;
use Exception;
use NextDeveloper\Blogs\Services\AccountsService;
use NextDeveloper\Commons\Actions\AbstractAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;
use NextDeveloper\Commons\Database\Models\Domains;
use NextDeveloper\Commons\Database\Models\Languages;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\Commons\Helpers\SlugHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class TranslatePost
 * This class is responsible for translating posts to different languages.
 *
 * @package NextDeveloper\Blogs\Actions\Posts
 */
class TranslatePost extends AbstractAction
{
    use TranslatablePostHelper;

    public const EVENTS = [
        'created:NextDeveloper\Blogs\Posts',
    ];

    // Constructor to initialize the Posts model

    /**
     * @throws NotAllowedException
     */
    public function __construct(Posts $model)
    {
        UserHelper::setUserById($model->iam_user_id);
        UserHelper::setCurrentAccountById($model->iam_account_id);

        $this->model = $model;

        parent::__construct();
    }

    /**
     * Main handler function to process post creation and initiate translations.
     *
     * @throws Exception
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Initiating post translation ...');

        DB::beginTransaction();

        try {
            // Skip processing if post is a draft or already a translation
            if ($this->shouldSkipProcessing()) {

                $this->setFinished('Post is a draft or is an alternate of another post, please and try again.');
                return;
            }

            // Retrieve the blog account associated with the post's domain
            $blogAccount = AccountsService::getBlogAccount($this->model);
            // 10% progress
            $this->setProgress(10, 'Retrieving blog account ...');

            if (!$blogAccount || !$blogAccount->is_auto_translate_enabled) {
                $this->setFinished('Auto-translation is not enabled for this blog account. Please enable it and try again.');
                return;
            }

            // Decode and clean alternates
            $alternates = $this->decodeAlternates($blogAccount->alternate);
            // 20% progress
            $this->setProgress(20, 'Alternates decoded ...');

            // Validate alternates against the database
            $targets = $this->getTranslationTargets($alternates);
            // 30% progress
            $this->setProgress(30, 'Alternates validated ...');

            // Create translations for each target locale
            foreach ($targets as $target) {
                // 40% progress
                $this->setProgress(40, "Translating post for locale: $target ...");
                $this->createTranslation($target);
            }

            // 90% progress
            $this->setProgress(90, 'Post translations created ...');

            DB::commit();
            // 100% progress
            $this->setFinished('Post translations created successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            $this->handleTranslationError($e);
        }
    }

    /**
     * Determines if processing should be skipped based on model state.
     */
    private function shouldSkipProcessing(): bool
    {
        return $this->model->is_draft || $this->model->alternate_of;
    }

    /**
     * Decodes alternates from JSON or returns an empty array if invalid.
     *
     * @param mixed $alternates
     * @return array
     */
    private function decodeAlternates(mixed $alternates): array
    {
        return is_string($alternates)
            ? json_decode($alternates, true) ?? []
            : ($alternates ?? []);
    }

    /**
     * Handles errors during translation processing.
     *
     * @param Exception $e
     * @throws Exception
     */
    private function handleTranslationError(Exception $e): void
    {
        Log::error('Post translation failed', [
            'post_id' => $this->model->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->setFinishedWithError('Post translation failed: ' . $e->getMessage());

        throw $e;
    }

    /**
     * Determines the target locales for translation based on alternate configuration.
     *
     * @param mixed $alternateConfig JSON string or array containing domain or language IDs.
     * @return array List of target locales for translation.
     */
    private function getTranslationTargets(mixed $alternateConfig): array
    {
        // Decode the alternate configuration if it's a JSON string
        $alternateConfig = is_string($alternateConfig) ? json_decode($alternateConfig, true) : $alternateConfig;

        // Check if there are language IDs specified in the configuration
        if (!empty($alternateConfig['common_language_ids'])) {

            // Query the Languages model without global scopes
            $query = Languages::withoutGlobalScopes();

            // Retrieve language codes matching the specified IDs
            return $query
                ->whereIn('id', $alternateConfig['common_language_ids'])
                ->pluck('iso_639_1_code')
                ->map(function($code) {
                    // Convert the language code to lowercase
                    return strtolower($code);
                })
                ->filter() // Remove any empty values
                ->unique() // Ensure each locale is unique
                ->values() // Reset the array keys
                ->toArray(); // Convert the collection to an array
        }

        // Return an empty array if no valid targets are found
        return [];
    }

    /**
     * @throws Exception|\Throwable
     */
    private function createTranslation(string $target): void
    {
        $target = strtolower(trim($target));

        DB::transaction(function() use ($target) {
            // Lock the original post for updates
            $lockedPost = Posts::where('id', $this->model->id)
                ->lockForUpdate()
                ->first();

            // Handle JSON decoding with proper type checking
            $alternates = is_string($lockedPost->alternates)
                ? json_decode($lockedPost->alternates ?? '[]', true) ?? []
                : (is_array($lockedPost->alternates) ? $lockedPost->alternates : []);

            $existingLocales = array_column($alternates, 'locale');

            if (in_array($target, $existingLocales, true)) {
                Log::warning('Duplicate locale prevented', [
                    'post_id' => $lockedPost->id,
                    'locale' => $target
                ]);
                return;
            }

            // Additional database check
            $existingTranslation = Posts::where('alternate_of', $lockedPost->id)
                ->where('locale', $target)
                ->exists();

            if ($existingTranslation) {
                Log::warning('Existing translation found in DB', [
                    'post_id' => $lockedPost->id,
                    'locale' => $target
                ]);
                return;
            }

            // Translate content
            $translatedContent = $this->translateContent($target);

            // add slug
            $translatedContent['slug'] = SlugHelper::generate(
                $translatedContent['title'] ?? $this->model->title,
                Posts::class
            );

            // Replace manual field assignments with:
            $translatedContent = array_merge(
                $translatedContent,
                $this->getCommonFields(),
                [
                    'alternate_of' => $this->model->id,
                    'locale' => $target,
                    'slug' => $translatedContent['slug']
                ]
            );

            $translatedPost = Posts::forceCreateQuietly($translatedContent);

            if (!$translatedPost) {
                throw new Exception("Failed to create translated post for locale: $target");
            }

            // Update original post's alternates with proper JSON encoding
            $newAlternate = [
                'id' => $translatedPost->id,
                'locale' => $target,
                'slug' => $translatedPost->slug
            ];

            // Update using the locked model instance
            $lockedPost->alternates = $this->cleanAlternates(
                array_merge($alternates, [$newAlternate])
            );


            $lockedPost->saveQuietly();
        });
    }
}
