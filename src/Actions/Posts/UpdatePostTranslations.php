<?php

namespace NextDeveloper\Blogs\Actions\Posts;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Helpers\SlugHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class UpdatePostTranslations
 * This class is responsible for updating the translations of a post.
 *
 * @package NextDeveloper\Blogs\Actions\Posts
 */
class UpdatePostTranslations extends AbstractAction
{
    use TranslatablePostHelper;


    public const EVENTS = [
        'updated:NextDeveloper\Blogs\Posts',
    ];

    // Constructor to initialize the Posts model
    public function __construct(Posts $model)
    {
        UserHelper::setUserById($model->iam_user_id);
        UserHelper::setCurrentAccountById($model->iam_account_id);

        $this->model = $model;

        parent::__construct();
    }

    /**
     * Main handler function to process post updates.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->shouldSkipProcessing()) {

            $this->setFinished('Post is a draft or is an alternate of another post, please and try again.');
            return;
        }

        DB::beginTransaction();

        try {

            // set progress
            $this->setProgress(0, 'Translating post alternates ...');

            // Decode and clean alternates
            $alternates = $this->decodeAlternates($this->model->alternates);
            // 10% progress
            $this->setProgress(10, 'Alternates decoded ...');

            // Validate alternates against the database
            $validAlternates = $this->validateAlternatesAgainstDB($alternates);
            // 20% progress
            $this->setProgress(20, 'Alternates validated ...');

            // Process translations for valid alternates
            $this->processTranslations($validAlternates);
            // 80% progress
            $this->setProgress(80, 'Translations processed ...');

            // Update model with valid alternates
            $this->updateModelAlternates($validAlternates);
            // 90% progress

            DB::commit();
            // 100% progress
            $this->setFinished('Post alternates updated successfully.');
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
        return $this->model->is_draft ||
               $this->model->alternate_of ||
               empty($this->model->alternates);
    }

    /**
     * Validates alternates against the database to ensure they exist.
     *
     * @param array $alternates
     * @return array
     */
    private function validateAlternatesAgainstDB(array $alternates): array
    {
        $validAlternates = [];
        foreach ($alternates as $alt) {
            $exists = Posts::withoutGlobalScopes()
            ->where('id', $alt['id'])
            ->where('locale', $alt['locale'])
            ->exists();

            if ($exists) {
                $validAlternates[] = $alt;
            } else {
                Log::warning('Removing invalid alternate', [
                    'post_id'   => $this->model->id,
                    'id'        => $alt['id']
                ]);
            }
        }
        return $validAlternates;
    }

    /**
     * Processes translations for each valid alternate.
     *
     * @param array $alternates
     */
    private function processTranslations(array $alternates): void
    {
        foreach ($alternates as $alternate) {
            $this->updateTranslation($alternate);
        }
    }

    /**
     * Updates the model's alternates field with valid alternates.
     *
     * @param array $alternates
     */
    private function updateModelAlternates(array $alternates): void
    {
        $this->model->alternates = $this->cleanAlternates($alternates);
        $this->model->saveQuietly();
    }

    /**
     * Handles errors during translation processing.
     *
     * @param Exception $e
     * @throws Exception
     */
    private function handleTranslationError(Exception $e): void
    {
        Log::error('Post alternate update failed', [
            'post_id' => $this->model->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->setFinishedWithError('Post alternate update failed: ' . $e->getMessage());

        throw $e;
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

    private function updateTranslation(array $alternate): void
    {
        try {
            $this->validateAlternate($alternate);
            $alternate['locale'] = strtolower(trim($alternate['locale']));
        } catch (\InvalidArgumentException $e) {
            Log::warning('Invalid alternate format', [
                'alternate' => $alternate,
                'error' => $e->getMessage()
            ]);
            return;
        }

        $translatedPost = Posts::withoutGlobalScopes()
            ->find($alternate['id']);

        if (!$translatedPost) {
            Log::warning('Alternate post not found', [
                'id' => $alternate['id'],
                'locale' => $alternate['locale']
            ]);
            return;
        }

        $locale = $alternate['locale'] ?? null;

        if (!$locale) {
            Log::warning('Alternate missing locale', ['alternate_id' => $alternate['id']]);
            return;
        }

        $translatedContent = $this->translateContent($locale);

        $translatedContent['slug'] = SlugHelper::generate($translatedContent['title'], Posts::class);

        $updateData = array_merge(
            $translatedContent,
            $this->getCommonFields(),
            [
                'locale' => $locale
            ]
        );

       $translatedPost->updateQuietly($updateData);

        $originalPost = Posts::find($translatedPost->alternate_of);
        if ($originalPost) {
            DB::transaction(function() use ($originalPost, $translatedPost) {
                $alternates = is_array($originalPost->alternates)
                    ? $originalPost->alternates
                    : json_decode($originalPost->alternates ?? '[]', true);

                foreach ($alternates as &$alt) {
                    if ($alt['id'] == $translatedPost->id) {
                        $alt['slug'] = $translatedPost->slug;
                        break;
                    }
                }

                $originalPost->alternates = $this->cleanAlternates($alternates);
                $originalPost->saveQuietly();
            });
        }
    }
}
