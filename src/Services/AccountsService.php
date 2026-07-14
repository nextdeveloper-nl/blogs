<?php

namespace NextDeveloper\Blogs\Services;

use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractAccountsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

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
        return Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $post->blog_account_id)
            ->where('is_auto_translate_enabled', true)
            ->first();
    }

    public static function getAlternates(Accounts $accounts)
    {
        $alternates = $accounts->alternate['blog_account_ids'];

        $accounts = [];

        foreach ($alternates as $alternate) {
            $accounts[] = Accounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $alternate)
                ->first();
        }

        return $accounts;
    }

    /**
     * Suspends the Blogs account belonging to the given IAM account, if one
     * exists. Blogs accounts are opt-in, so a customer without one is a
     * no-op here, not an error.
     */
    public static function suspendWithIamAccount(\NextDeveloper\IAM\Database\Models\Accounts $account): ?Accounts
    {
        $blogAccount = Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_account_id', $account->id)
            ->first();

        if (!$blogAccount) {
            return null;
        }

        $blogAccount->update([
            'is_suspended' => true,
        ]);

        return $blogAccount->fresh();
    }
}
