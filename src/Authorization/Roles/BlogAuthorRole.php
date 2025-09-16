<?php

namespace NextDeveloper\Blogs\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class BlogAuthorRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'blog-author';

    public const LEVEL = 120;

    public const DESCRIPTION = 'Blog Author';

    public const DB_PREFIX = 'blog';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where([
            'iam_account_id'    =>  UserHelper::currentAccount()->id,
            'iam_user_id'       =>  UserHelper::me()->id
        ]);
    }

    public function checkPrivileges(Users $users = null)
    {
        //return UserHelper::hasRole(self::NAME, $users);
    }

    public function getModule()
    {
        return 'blogs';
    }

    public function allowedOperations() :array
    {
        return [
            'blog_posts:read',
            'blog_posts:update',
            'blog_posts:create',
            'blog_posts:delete',

            'blog_posts_perspective:read',
            'blog_accounts_perspective:read',
        ];
    }

    public function getLevel(): int
    {
        return self::LEVEL;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function canBeApplied($column)
    {
        if(self::DB_PREFIX === '*') {
            return true;
        }

        if(Str::startsWith($column, self::DB_PREFIX)) {
            return true;
        }

        return false;
    }

    public function getDbPrefix()
    {
        return self::DB_PREFIX;
    }
}
