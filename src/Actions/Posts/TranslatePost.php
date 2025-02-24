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
use NextDeveloper\I18n\Services\I18nTranslationService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
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
    public function __construct(Posts $model, $params = null, $previousAction = null)
    {
        $this->model = $model;

        parent::__construct($params, $previousAction);
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

            if(!$blogAccount) {
                $this->setFinished('Cannot find a blog account for this blog post. This is weird because there should be. Please consult to your Leo provider.');
            }

            if (!$blogAccount->is_auto_translate_enabled) {
                $this->setFinished('Auto-translation is not enabled for this blog account. Please enable it and try again.');
                return;
            }

            if(!array_key_exists('blog_account_ids', $blogAccount->alternate))
                $this->setFinished('Cannot find a destination language to translate the post. Please check for the blog account alternates.');

            $this->setProgress(20, 'Alternates decoded ...');

            foreach ($blogAccount->alternate['blog_account_ids'] as $alternate) {
                $destinationAccount = AccountsService::getById($alternate);
                $targetLanguage = self::getTranslationTarget($destinationAccount->common_language_id);
                $this->setProgress(30, 'Translating to: ' . $targetLanguage->code);

                $this->createTranslation($targetLanguage,  $destinationAccount);
            }

            // 90% progress
            $this->setProgress(90, 'Post translations created ...');

            // 100% progress
            $this->setFinished('Post translations created successfully.');
        } catch (Exception $e) {
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
    private function getTranslationTarget($commonLanguageId): Languages
    {
        return Languages::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $commonLanguageId)
            ->first();
    }

    /**
     * @throws Exception|\Throwable
     */
    private function createTranslation(Languages $target, Accounts $destinationAccount): void
    {
        // Lock the original post for updates
        $lockedPost = Posts::where('id', $this->model->id)
            ->lockForUpdate()
            ->first();

        // Handle JSON decoding with proper type checking
        $alternates = is_string($lockedPost->alternates)
            ? json_decode($lockedPost->alternates ?? '[]', true) ?? []
            : (is_array($lockedPost->alternates) ? $lockedPost->alternates : []);

        $existingLocales = array_column($alternates, 'locale');

        if (in_array($target->code, $existingLocales, true)) {
            Log::warning('Duplicate locale prevented', [
                'post_id' => $lockedPost->id,
                'locale' => $target
            ]);
            return;
        }

        // Additional database check
        $existingTranslation = Posts::where('alternate_of', $lockedPost->id)
            ->where('locale', trim($target->code))
            ->exists();

        if ($existingTranslation) {
            Log::warning('Existing translation found in DB', [
                'post_id' => $lockedPost->id,
                'locale' => $target->code
            ]);
            return;
        }

        /**
         * Making translations
         */
        $title = I18nTranslationService::translate($this->model->title, $target->code);
        $body = I18nTranslationService::translate($this->model->body, $target->code);

        $metaTitle = null;

        //  Check title
        if(!$this->model->meta_title) {
            $metaTitle = $title;
        } else {
            $metaTitle = I18nTranslationService::translate($this->model->meta_title, $target->code);
        }

        $metaDescription = null;

        if($this->model->meta_description) {
            $description = I18nTranslationService::translate($this->model->meta_description, $target->code);
        }

        $metaKeywords = null;

        if($this->model->meta_keywords) {
            $metaKeywords= I18nTranslationService::translate($this->model->meta_keywords, $target->code);
        }

        //  Creating the translated blog object
        $translatedContent = [
            'title' =>  $title ? $title->translation : $this->model->title,
            'body'  =>  $body ? $body->translation : $this->model->body,
        ];

        $translatedContent = array_merge($translatedContent, [
            'meta_title'    =>  $metaTitle ? $metaTitle->translation : $translatedContent['title'],
            'meta_description'  =>   $metaDescription ? $metaDescription->translation : $this->model->meta_description,
            'meta_keywords'  =>   $metaKeywords ? $metaKeywords->translation : $this->model->meta_keywords
        ]);

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
                'locale' => trim($target->code),
                'slug' => $translatedContent['slug'],
                'blog_account_id'   =>  $destinationAccount->id
            ]
        );

        $translatedPost = Posts::forceCreateQuietly($translatedContent);

        if (!$translatedPost) {
            throw new Exception("Failed to create translated post for locale: $target");
        }

        // Update original post's alternates with proper JSON encoding
        $newAlternate = [
            'id' => $translatedPost->id,
            'locale' => trim($target->code),
            'slug' => $translatedPost->slug
        ];

        // Update using the locked model instance
        $lockedPost->alternates = $this->cleanAlternates(
            array_merge($alternates, [$newAlternate])
        );


        $lockedPost->saveQuietly();
    }
}
