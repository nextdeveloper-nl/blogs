<?php

namespace NextDeveloper\Blogs\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class PostsPerspectiveQueryFilter extends AbstractQueryFilter
{
    /**
     * Filter by tags
     *
     * @param  $values
     * @return Builder
     */
    public function tags($values)
    {
        $tags = explode(',', $values);

        $search = '';

        for($i = 0; $i < count($tags); $i++) {
            $search .= "'" . trim($tags[$i]) . "',";
        }

        $search = substr($search, 0, -1);

        return $this->builder->whereRaw('tags @> ARRAY[' . $search . ']');
    }

    /**
     * @var Builder
     */
    protected $builder;

    public function title($value)
    {
        return $this->builder->where('title', 'like', '%' . $value . '%');
    }

    public function body($value)
    {
        return $this->builder->where('body', 'like', '%' . $value . '%');
    }

    public function slug($value)
    {
        return $this->builder->where('slug', $value);
    }

    public function headerImage($value)
    {
        return $this->builder->where('header_image', 'like', '%' . $value . '%');
    }

    public function metaTitle($value)
    {
        return $this->builder->where('meta_title', 'like', '%' . $value . '%');
    }

    public function metaDescription($value)
    {
        return $this->builder->where('meta_description', 'like', '%' . $value . '%');
    }

    public function metaKeywords($value)
    {
        return $this->builder->where('meta_keywords', 'like', '%' . $value . '%');
    }

    public function author($value)
    {
        return $this->builder->where('author', 'like', '%' . $value . '%');
    }

    public function team($value)
    {
        return $this->builder->where('team', 'like', '%' . $value . '%');
    }

    public function category($value)
    {
        return $this->builder->where('category', 'like', '%' . $value . '%');
    }

    public function domainName($value)
    {
        return $this->builder->where('domain_name', 'like', '%' . $value . '%');
    }

    public function replyCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('reply_count', $operator, $value);
    }

    public function readCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('read_count', $operator, $value);
    }

    public function bonusPoints($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('bonus_points', $operator, $value);
    }

    public function isActive($value)
    {


        return $this->builder->where('is_active', $value);
    }

    public function isLocked($value)
    {


        return $this->builder->where('is_locked', $value);
    }

    public function isPinned($value)
    {


        return $this->builder->where('is_pinned', $value);
    }

    public function isDraft($value)
    {


        return $this->builder->where('is_draft', $value);
    }

    public function isMarkdown($value)
    {


        return $this->builder->where('is_markdown', $value);
    }

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    public function commonCategoryId($value)
    {
            $commonCategory = \NextDeveloper\Commons\Database\Models\Categories::where('uuid', $value)->first();

        if($commonCategory) {
            return $this->builder->where('common_category_id', '=', $commonCategory->id);
        }
    }

    public function commonDomainId($value)
    {
        $commonDomain = \NextDeveloper\Commons\Database\Models\Domains::where('uuid', $value)->first();

        if($commonDomain) {
            return $this->builder->where('common_domain_id', '=', $commonDomain->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
