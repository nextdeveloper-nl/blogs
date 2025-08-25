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

class BlogReaderRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'blog-reader';

    public const LEVEL = 150;

    public const DESCRIPTION = 'Blog Reader';

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
        //  This should change because in the future if we try to implement NON-HTTP request, this will not work.
        if(request()->getMethod() == 'GET') {
            $builder->where([
                'is_public' =>  true
            ]);
        }
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
            'blogs_posts:read',
            'blogs_posts:comment',
            'blogs_posts:like',
            'blogs_posts:dislike',
            'blogs_posts:report',
            'blogs_posts:share',

            'blogs_posts_perspective:read',
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

    public function checkRules(Users $users): bool
    {
        // TODO: Implement checkRules() method.
    }
}
