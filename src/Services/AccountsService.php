<?php

namespace NextDeveloper\Blogs\Services;

use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractAccountsService;

/**
 * This class is responsible from managing the data for Accounts
 *
 * Class AccountsService.
 *
 * @package NextDeveloper\Blogs\Database\Models
 */
class AccountsService extends AbstractAccountsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function getBlogAccount(Posts $post) :?Accounts
    {
        return Accounts::withoutGlobalScopes()
            ->where('id', $post->blog_account_id)
            ->where('is_auto_translate_enabled', true)
            ->first();
    }
}
