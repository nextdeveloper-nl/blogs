<?php

namespace NextDeveloper\Blogs\Actions\Posts;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class RemovePostTranslations
 * This class is responsible for removing all translations of a post and keeping only the original.
 */
class RemovePostTranslations extends AbstractAction
{

    public const EVENTS = [
        'deleting:NextDeveloper\Blogs\Posts',
    ];


    /**
     * Constructor to initialize the Posts model
     *
     * @throws NotAllowedException
     */
    public function __construct(Posts $model, $params = null, $previousAction = null)
    {
        UserHelper::setUserById($model->iam_user_id);
        UserHelper::setCurrentAccountById($model->iam_account_id);

        $this->model = $model;

        parent::__construct($params, $previousAction);
    }

    /**
     * Main handler function to remove post translations.
     *
     * @throws Exception|\Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Initiating post translation removal...');

        DB::beginTransaction();

        try {
            // 10% progress
            $this->setProgress(10, 'Finding translation posts...');

            // Find all posts that are translations of this post
            $translatedPosts = $this->findTranslatedPosts();

            // 30% progress
            $this->setProgress(30, 'Found ' . count($translatedPosts) . ' translation(s) to remove...');

            // Remove all translated posts
            $this->removeTranslatedPosts($translatedPosts);

            // 70% progress
            $this->setProgress(70, 'Translated posts removed...');

            // Clear alternates field from the original post
            $this->clearAlternates();

            // 90% progress
            $this->setProgress(90, 'Clearing alternates from original post...');

            DB::commit();

            // 100% progress
            $this->setFinished('Post translations removed successfully. Only the original post remains.');

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleError($e);
        }
    }

    /**
     * Find all posts that are translations of the current post
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function findTranslatedPosts(): \Illuminate\Database\Eloquent\Collection
    {
        return Posts::withoutGlobalScope(AuthorizationScope::class)
            ->where('alternate_of', $this->model->id)
            ->get();
    }

    /**
     * Remove all translated posts
     *
     * @param \Illuminate\Database\Eloquent\Collection $translatedPosts
     * @throws Exception
     */
    private function removeTranslatedPosts(\Illuminate\Database\Eloquent\Collection $translatedPosts): void
    {
        foreach ($translatedPosts as $translatedPost) {
            try {
                Log::info("Removing translated post: {$translatedPost->id} (slug: {$translatedPost->slug})");
                $translatedPost->delete();
            } catch (Exception $e) {
                Log::error("Failed to delete translated post {$translatedPost->id}: " . $e->getMessage());
                throw new Exception("Failed to remove translation post {$translatedPost->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Clear the alternates field from the original post
     *
     * @throws Exception
     */
    private function clearAlternates(): void
    {
        try {
            $this->model->updateQuietly(['alternates' => null]);

            Log::info("Cleared alternates from original post: {$this->model->id}");
        } catch (Exception $e) {
            Log::error("Failed to clear alternates from post {$this->model->id}: " . $e->getMessage());
            throw new Exception("Failed to clear alternates from original post: " . $e->getMessage());
        }
    }

    /**
     * Handle translation errors
     *
     * @param Exception $e
     */
    private function handleError(Exception $e): void
    {
        Log::error('Post translation removal failed: ' . $e->getMessage(), [
            'post_id' => $this->model->id,
            'post_slug' => $this->model->slug,
            'trace' => $e->getTraceAsString()
        ]);

        $this->setFinished('Post translation removal failed: ' . $e->getMessage());
    }
}
