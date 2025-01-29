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
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Commons\Database\Models\Domains;
use NextDeveloper\Commons\Database\Models\Languages;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;

class PostCreatedEvent implements ShouldQueue
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

        DB::beginTransaction();

        try {
            // Skip processing if post is a draft or already a translation
            if ($this->model->is_draft || $this->model->alternate_of) {
                return;
            }

            $blogAccount = $this->getBlogAccount();

            if (!$blogAccount || !$blogAccount->is_auto_translate_enabled) {
                return;
            }

            // Fix the alternate field decoding
            $alternates = is_string($blogAccount->alternate)
                ? json_decode($blogAccount->alternate, true)
                : $blogAccount->alternate;

            $alternates = $alternates ?? [];

            $targets = $this->getTranslationTargets($alternates);

            foreach ($targets as $target) {
                $this->createTranslation($target);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Post translation failed', [
                'post_id' => $this->model->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    private function getBlogAccount(): ?Accounts
    {
        $query = Accounts::withoutGlobalScopes();

        return $query
            ->where('common_domain_id', $this->model->common_domain_id)
            ->where('is_auto_translate_enabled', true)
            ->first();
    }

    private function getTranslationTargets($alternateConfig): array
    {

        $alternateConfig = is_string($alternateConfig) ? json_decode($alternateConfig, true) : $alternateConfig;

        if (!empty($alternateConfig['common_domain_ids'])) {

            $query = Domains::withoutGlobalScopes();

            return $query
                ->whereIn('id', $alternateConfig['common_domain_ids'])
                ->get()
                ->map(function($domain) {
                    $parts = explode('.', $domain->name);
                    return end($parts);
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        if (!empty($alternateConfig['common_language_ids'])) {

            $query = Languages::withoutGlobalScopes();

            return $query
                ->whereIn('id', $alternateConfig['common_language_ids'])
                ->pluck('iso_639_1_code')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        return [];
    }

    /**
     * @throws Exception
     */
    private function createTranslation(string $target): void
    {
        // Fix alternates decoding
        $alternates = is_string($this->model->alternates)
            ? json_decode($this->model->alternates ?? '[]', true)
            : $this->model->alternates;

        $alternates = $alternates ?? [];

        // Check for existing translation using strict array comparison
        $existingLocales = array_column($alternates, 'locale');
        if (in_array($target, $existingLocales, true)) {
            return;
        }

        // Translate content
        $translatedContent = $this->translateContent($target);

        // Replace manual field assignments with:
        $translatedContent = array_merge(
            $translatedContent,
            $this->getCommonFields(),
            [
                'alternate_of' => $this->model->id,
                'locale' => $target,
            ]
        );

        $translatedPost = PostsService::create($translatedContent);

        if (!$translatedPost) {
            throw new Exception("Failed to create translated post for locale: $target");
        }

        // Update original post's alternates with proper JSON encoding
        $newAlternate = [
            'id' => $translatedPost->id,
            'locale' => $target,
            'slug' => $translatedPost->slug,
            'uuid' => $translatedPost->uuid
        ];

        $updatedAlternates = array_merge($alternates, [$newAlternate]);
        $this->model->alternates = $this->cleanAlternates($updatedAlternates);
        $this->model->save();
    }
}
