<?php

namespace NextDeveloper\Blogs\Actions\Posts;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\IAM\Helpers\UserHelper;
use Throwable;

/**
 * Re-translates and updates every alternate attached to a post whenever the
 * source post itself is updated. Each alternate is handled in its own
 * transaction so that a failure on one locale does not corrupt the others.
 */
class UpdatePostTranslations extends AbstractAction
{
    use TranslatablePostHelper;

    public const EVENTS = [
        'updated:NextDeveloper\Blogs\Posts',
    ];

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
     * @throws Exception|Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Initiating post translation update ...');

        try {
            if ($this->shouldSkipProcessing()) {
                $this->setFinished('Post is a draft, an alternate, or has no translations to update.');

                return;
            }

            $alternates = $this->normalizeAlternates($this->model->alternates);
            $this->setProgress(10, 'Alternates decoded ...');

            $validAlternates = $this->filterValidAlternates($alternates);
            $this->setProgress(20, 'Alternates validated ...');

            if (empty($validAlternates)) {
                $this->setFinished('No valid alternates found to update.');

                return;
            }

            $this->updateAlternates($validAlternates);

            $this->syncAlternatesColumn($validAlternates);

            $this->setProgress(100, 'Post alternates updated successfully.');
            $this->setFinished('Post alternates updated successfully.');
        } catch (Exception $e) {
            $this->handleTranslationError($e);
        }
    }

    private function shouldSkipProcessing(): bool
    {
        return $this->model->is_draft
            || $this->model->alternate_of
            || empty($this->model->alternates);
    }

    /**
     * Drops any alternate rows that no longer exist in the database.
     *
     * @param  array<int, array<string, mixed>>  $alternates
     * @return array<int, array<string, mixed>>
     */
    private function filterValidAlternates(array $alternates): array
    {
        $valid = [];

        foreach ($alternates as $alternate) {
            if (empty($alternate['id']) || empty($alternate['locale'])) {
                continue;
            }

            $exists = Posts::withoutGlobalScopes()
                ->where('id', $alternate['id'])
                ->where('locale', $alternate['locale'])
                ->exists();

            if ($exists) {
                $valid[] = $alternate;

                continue;
            }

            Log::warning('UpdatePostTranslations: dropping stale alternate', [
                'post_id' => $this->model->id,
                'alternate_id' => $alternate['id'],
                'locale' => $alternate['locale'] ?? null,
            ]);
        }

        return $valid;
    }

    /**
     * Re-translates each alternate, reporting progress and catching per-alternate
     * failures so a single broken locale cannot block the rest.
     *
     * @param  array<int, array<string, mixed>>  $alternates
     */
    private function updateAlternates(array $alternates): void
    {
        $total = count($alternates);
        $baseProgress = 20;
        $range = 70;

        foreach ($alternates as $index => $alternate) {
            $progress = (int) ($baseProgress + ($range * ($index / max($total, 1))));
            $this->setProgress($progress, "Updating translation for {$alternate['locale']} ...");

            try {
                $this->updateTranslation($alternate);
            } catch (Throwable $e) {
                Log::error('UpdatePostTranslations: alternate update failed', [
                    'post_id' => $this->model->id,
                    'alternate_id' => $alternate['id'] ?? null,
                    'locale' => $alternate['locale'] ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Re-translates one alternate post and refreshes its content/slug.
     *
     * @param  array<string, mixed>  $alternate
     *
     * @throws Throwable
     */
    private function updateTranslation(array $alternate): void
    {
        try {
            $this->validateAlternate($alternate);
        } catch (\InvalidArgumentException $e) {
            Log::warning('UpdatePostTranslations: invalid alternate format', [
                'alternate' => $alternate,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        $locale = strtolower(trim($alternate['locale']));

        $sourceCode = strtolower(trim((string) $this->model->locale));

        if ($sourceCode !== '' && $sourceCode === $locale) {
            Log::info('UpdatePostTranslations: target locale matches source; skipping', [
                'post_id' => $this->model->id,
                'locale' => $locale,
            ]);

            return;
        }

        DB::transaction(function () use ($alternate, $locale): void {
            $translatedPost = Posts::withoutGlobalScopes()
                ->where('id', $alternate['id'])
                ->lockForUpdate()
                ->first();

            if (!$translatedPost) {
                Log::warning('UpdatePostTranslations: alternate post vanished', [
                    'alternate_id' => $alternate['id'],
                    'locale' => $locale,
                ]);

                return;
            }

            $translatedPayload = $this->buildTranslatedPayload($locale);

            if ($translatedPayload['title'] === $this->model->title) {
                Log::warning('UpdatePostTranslations: translation service returned source text unchanged; skipping locale', [
                    'post_id' => $this->model->id,
                    'locale' => $locale,
                ]);

                return;
            }

            $payload = array_merge(
                $translatedPayload,
                $this->getCommonFields(),
                [
                    'locale' => $locale,
                    'alternate_of' => $this->model->id,
                    'blog_account_id' => $translatedPost->blog_account_id,
                    'common_domain_id' => $translatedPost->common_domain_id,
                ]
            );

            $translatedPost->updateQuietly($payload);
        });
    }

    /**
     * Normalizes the alternates column on the source post so each entry
     * reflects the latest slug/title of the translated post.
     *
     * @param  array<int, array<string, mixed>>  $alternates
     */
    private function syncAlternatesColumn(array $alternates): void
    {
        $ids = array_column($alternates, 'id');

        if (empty($ids)) {
            return;
        }

        $fresh = Posts::withoutGlobalScopes()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $updated = [];

        foreach ($alternates as $alternate) {
            $post = $fresh->get($alternate['id']);

            if (!$post) {
                continue;
            }

            $updated[] = [
                'id' => $post->id,
                'locale' => strtolower(trim($alternate['locale'])),
                'title' => $post->title,
                'slug' => $post->slug,
            ];
        }

        $this->model->alternates = $this->cleanAlternates($updated);
        $this->model->saveQuietly();
    }

    /**
     * @throws Exception
     */
    private function handleTranslationError(Exception $e): void
    {
        Log::error('Post alternate update failed', [
            'post_id' => $this->model->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        $this->setFinishedWithError('Post alternate update failed: ' . $e->getMessage());

        throw $e;
    }
}
