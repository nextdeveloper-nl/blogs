<?php

namespace NextDeveloper\Blogs\Actions\Posts;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;
use NextDeveloper\Blogs\Services\AccountsService;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Database\Models\Languages;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use Throwable;

/**
 * Translates a blog post into every language configured on the account's
 * alternate blog accounts and links the translated post back to the original.
 */
class TranslatePost extends AbstractAction
{
    use TranslatablePostHelper;

    public const EVENTS = [
        'created:NextDeveloper\Blogs\Posts',
    ];

    /**
     * @throws NotAllowedException
     */
    public function __construct(Posts $model, $params = null, $previousAction = null)
    {
        $this->model = $model;

        parent::__construct($params, $previousAction);
    }

    /**
     * @throws Exception|Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Initiating post translation ...');

        try {
            if ($this->shouldSkipProcessing()) {
                $this->setFinished('Post is a draft or an alternate of another post; skipping translation.');

                return;
            }

            $blogAccount = AccountsService::getBlogAccount($this->model);
            $this->setProgress(10, 'Retrieving blog account ...');

            if (! $blogAccount) {
                $this->setFinished('Cannot find a blog account for this post. Please consult your Leo provider.');

                return;
            }

            if (! $blogAccount->is_auto_translate_enabled) {
                $this->setFinished('Auto-translation is disabled for this blog account.');

                return;
            }

            $targetAccountIds = $blogAccount->alternate['blog_account_ids'] ?? [];

            if (empty($targetAccountIds)) {
                $this->setFinished('No destination languages configured on this blog account.');

                return;
            }

            $this->translateToTargets($targetAccountIds);

            $this->setProgress(100, 'Post translations created successfully.');
            $this->setFinished('Post translations created successfully.');
        } catch (Exception $e) {
            $this->handleTranslationError($e);
        }
    }

    /**
     * Iterates over every target account and creates a translated post for each.
     *
     * @param  array<int, int>  $targetAccountIds
     */
    private function translateToTargets(array $targetAccountIds): void
    {
        $total = count($targetAccountIds);
        $baseProgress = 20;
        $range = 70;

        foreach ($targetAccountIds as $index => $accountId) {
            $progress = (int) ($baseProgress + ($range * ($index / max($total, 1))));

            $destinationAccount = AccountsService::getById($accountId);

            if (! $destinationAccount) {
                Log::warning('TranslatePost: destination account not found', [
                    'post_id' => $this->model->id,
                    'account_id' => $accountId,
                ]);

                continue;
            }

            $targetLanguage = $this->resolveLanguage($destinationAccount->common_language_id);

            if (! $targetLanguage) {
                Log::warning('TranslatePost: target language not found', [
                    'post_id' => $this->model->id,
                    'account_id' => $destinationAccount->id,
                    'common_language_id' => $destinationAccount->common_language_id,
                ]);

                continue;
            }

            $this->setProgress($progress, "Translating to {$targetLanguage->code} ...");

            try {
                $this->createTranslation($targetLanguage, $destinationAccount);
            } catch (Throwable $e) {
                Log::error('TranslatePost: failed to create translation for target', [
                    'post_id' => $this->model->id,
                    'target_locale' => $targetLanguage->code,
                    'destination_account_id' => $destinationAccount->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function shouldSkipProcessing(): bool
    {
        return $this->model->is_draft || $this->model->alternate_of;
    }

    private function resolveLanguage($commonLanguageId): ?Languages
    {
        if (! $commonLanguageId) {
            return null;
        }

        return Languages::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $commonLanguageId)
            ->first();
    }

    /**
     * @throws Throwable
     */
    private function createTranslation(Languages $target, Accounts $destinationAccount): void
    {
        DB::transaction(function () use ($target, $destinationAccount): void {
            $lockedPost = Posts::where('id', $this->model->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedPost) {
                return;
            }

            if ($this->translationAlreadyExists($lockedPost, $target, $destinationAccount)) {
                Log::info('TranslatePost: translation already exists; skipping', [
                    'post_id' => $lockedPost->id,
                    'locale' => $target->code,
                    'destination_account_id' => $destinationAccount->id,
                ]);

                return;
            }

            $locale = trim($target->code);

            $payload = array_merge(
                $this->buildTranslatedPayload($locale),
                $this->getCommonFields(),
                [
                    'alternate_of' => $this->model->id,
                    'locale' => $locale,
                    'common_domain_id' => $destinationAccount->common_domain_id,
                    'blog_account_id' => $destinationAccount->id,
                ]
            );

            $translatedPost = Posts::forceCreateQuietly($payload);

            if (! $translatedPost) {
                throw new Exception("Failed to create translated post for locale: {$locale}");
            }

            $this->linkAlternate($lockedPost, $translatedPost, $locale);
        });
    }

    private function translationAlreadyExists(Posts $lockedPost, Languages $target, Accounts $destinationAccount): bool
    {
        $alternates = $this->normalizeAlternates($lockedPost->alternates);
        $existingLocales = array_column($alternates, 'locale');

        if (in_array($target->code, $existingLocales, true)) {
            return true;
        }

        return Posts::where('alternate_of', $lockedPost->id)
            ->where(function ($query) use ($target, $destinationAccount): void {
                $query->where('locale', trim($target->code))
                    ->orWhere('blog_account_id', $destinationAccount->id);
            })
            ->exists();
    }

    private function linkAlternate(Posts $originalPost, Posts $translatedPost, string $locale): void
    {
        $alternates = $this->normalizeAlternates($originalPost->alternates);

        $alternates[] = [
            'id' => $translatedPost->id,
            'locale' => $locale,
            'title' => $translatedPost->title,
            'slug' => $translatedPost->slug,
        ];

        $originalPost->alternates = $this->cleanAlternates($alternates);
        $originalPost->saveQuietly();
    }

    /**
     * @throws Exception
     */
    private function handleTranslationError(Exception $e): void
    {
        Log::error('Post translation failed', [
            'post_id' => $this->model->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        $this->setFinishedWithError('Post translation failed: '.$e->getMessage());

        throw $e;
    }
}
