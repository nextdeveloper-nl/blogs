<?php

namespace NextDeveloper\Blogs\Actions\Accounts;

use App\Models\User;
use Exception;
use NextDeveloper\Blogs\Actions\Posts\TranslatePost;
use NextDeveloper\Blogs\Services\AccountsService;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\Commons\Actions\AbstractAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Helpers\TranslatablePostHelper;
use NextDeveloper\Commons\Database\GlobalScopes\LimitScope;
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
class TranslateAllPosts extends AbstractAction
{
    use TranslatablePostHelper;

    public const EVENTS = [
        'created:NextDeveloper\Blogs\Posts',
    ];

    // Constructor to initialize the Posts model

    /**
     * @throws NotAllowedException
     */
    public function __construct(Accounts $account, $params = null, $previousAction = null)
    {
        $this->model = $account;

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

        if(!$this->model->is_auto_translate_enabled) {
            $this->setFinished('Auto translation is disabled');
            return;
        }

        $posts = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->withoutGlobalScope(LimitScope::class)
            ->where('blog_account_id', $this->model->id)
            ->get();

        $alternates = AccountsService::getAlternates($this->model);

        foreach ($posts as $post) {
            foreach ($alternates as $alternate) {
                $alternatePost = Posts::withoutGlobalScope(AuthorizationScope::class)
                    ->where('blog_account_id', $alternate->id)
                    ->where('alternate_of', $post->id)
                    ->first();

                if(!$alternatePost) {
                    dispatch(new TranslatePost($post));
                }
            }
        }

        $this->setFinished('Translation trigger is finished');
    }
}
