<?php

namespace NextDeveloper\Blogs\EventHandlers\Posts;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;

class PostUpdatedEvent implements ShouldQueue
{
    use TranslatablePostHelper, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Posts $model
    ) {}

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        // Skip if post is a draft, translation, or has no alternates
        if ($this->model->is_draft || $this->model->alternate_of || empty($this->model->alternates)) {
            return;
        }

        DB::beginTransaction();

        try {
            $alternates = is_string($this->model->alternates)
                ? json_decode($this->model->alternates ?? '[]', true)
                : $this->model->alternates;

            $alternates = $this->cleanAlternates($alternates ?? []);

            foreach ($alternates as $alternate) {
                $this->updateTranslation($alternate);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Post alternate update failed', [
                'post_id' => $this->model->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    private function updateTranslation(array $alternate): void
    {
        try {
            $this->validateAlternate($alternate);
        } catch (\InvalidArgumentException $e) {
            Log::warning('Invalid alternate format', [
                'alternate' => $alternate,
                'error' => $e->getMessage()
            ]);
            return;
        }

        $translatedPost = Posts::withoutGlobalScopes()
            ->where('uuid', $alternate['uuid'])
            ->first();

        if (!$translatedPost) {
            Log::warning('Alternate post not found', [
                'uuid' => $alternate['uuid'],
                'locale' => $alternate['locale']
            ]);
            return;
        }

        // Get locale from alternate data instead of column
        $locale = $alternate['locale'] ?? null;

        if (!$locale) {
            Log::warning('Alternate missing locale', ['alternate_id' => $alternate['id']]);
            return;
        }

        $translatedContent = $this->translateContent($locale);

        $updateData = array_merge(
            $translatedContent,
            $this->getCommonFields(),
            [
                'locale' => $locale
            ]
        );

        PostsService::update($translatedPost->uuid, $updateData);
    }
}
