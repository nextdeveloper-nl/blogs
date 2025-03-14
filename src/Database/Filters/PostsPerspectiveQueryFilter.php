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
    
    public function slug($value)
    {
        return $this->builder->where('slug', 'like', '%' . $value . '%');
    }

        
    public function title($value)
    {
        return $this->builder->where('title', 'like', '%' . $value . '%');
    }

        
    public function body($value)
    {
        return $this->builder->where('body', 'like', '%' . $value . '%');
    }

        
    public function abstract($value)
    {
        return $this->builder->where('abstract', 'like', '%' . $value . '%');
    }

        
    public function headerImage($value)
    {
        return $this->builder->where('header_image', 'like', '%' . $value . '%');
    }

        //  This is an alias function of headerImage
    public function header_image($value)
    {
        return $this->headerImage($value);
    }
        
    public function metaTitle($value)
    {
        return $this->builder->where('meta_title', 'like', '%' . $value . '%');
    }

        //  This is an alias function of metaTitle
    public function meta_title($value)
    {
        return $this->metaTitle($value);
    }
        
    public function metaDescription($value)
    {
        return $this->builder->where('meta_description', 'like', '%' . $value . '%');
    }

        //  This is an alias function of metaDescription
    public function meta_description($value)
    {
        return $this->metaDescription($value);
    }
        
    public function metaKeywords($value)
    {
        return $this->builder->where('meta_keywords', 'like', '%' . $value . '%');
    }

        //  This is an alias function of metaKeywords
    public function meta_keywords($value)
    {
        return $this->metaKeywords($value);
    }
        
    public function locale($value)
    {
        return $this->builder->where('locale', 'like', '%' . $value . '%');
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

        //  This is an alias function of domainName
    public function domain_name($value)
    {
        return $this->domainName($value);
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

        //  This is an alias function of replyCount
    public function reply_count($value)
    {
        return $this->replyCount($value);
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

        //  This is an alias function of readCount
    public function read_count($value)
    {
        return $this->readCount($value);
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

        //  This is an alias function of bonusPoints
    public function bonus_points($value)
    {
        return $this->bonusPoints($value);
    }
    
    public function alternateOf($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('alternate_of', $operator, $value);
    }

        //  This is an alias function of alternateOf
    public function alternate_of($value)
    {
        return $this->alternateOf($value);
    }
    
    public function isActive($value)
    {
        return $this->builder->where('is_active', $value);
    }

        //  This is an alias function of isActive
    public function is_active($value)
    {
        return $this->isActive($value);
    }
     
    public function isLocked($value)
    {
        return $this->builder->where('is_locked', $value);
    }

        //  This is an alias function of isLocked
    public function is_locked($value)
    {
        return $this->isLocked($value);
    }
     
    public function isPinned($value)
    {
        return $this->builder->where('is_pinned', $value);
    }

        //  This is an alias function of isPinned
    public function is_pinned($value)
    {
        return $this->isPinned($value);
    }
     
    public function isDraft($value)
    {
        return $this->builder->where('is_draft', $value);
    }

        //  This is an alias function of isDraft
    public function is_draft($value)
    {
        return $this->isDraft($value);
    }
     
    public function isMarkdown($value)
    {
        return $this->builder->where('is_markdown', $value);
    }

        //  This is an alias function of isMarkdown
    public function is_markdown($value)
    {
        return $this->isMarkdown($value);
    }
     
    public function createdAtStart($date)
    {
        return $this->builder->where('created_at', '>=', $date);
    }

    public function createdAtEnd($date)
    {
        return $this->builder->where('created_at', '<=', $date);
    }

    //  This is an alias function of createdAt
    public function created_at_start($value)
    {
        return $this->createdAtStart($value);
    }

    //  This is an alias function of createdAt
    public function created_at_end($value)
    {
        return $this->createdAtEnd($value);
    }

    public function updatedAtStart($date)
    {
        return $this->builder->where('updated_at', '>=', $date);
    }

    public function updatedAtEnd($date)
    {
        return $this->builder->where('updated_at', '<=', $date);
    }

    //  This is an alias function of updatedAt
    public function updated_at_start($value)
    {
        return $this->updatedAtStart($value);
    }

    //  This is an alias function of updatedAt
    public function updated_at_end($value)
    {
        return $this->updatedAtEnd($value);
    }

    public function deletedAtStart($date)
    {
        return $this->builder->where('deleted_at', '>=', $date);
    }

    public function deletedAtEnd($date)
    {
        return $this->builder->where('deleted_at', '<=', $date);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_start($value)
    {
        return $this->deletedAtStart($value);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_end($value)
    {
        return $this->deletedAtEnd($value);
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

    
    public function commonDomainId($value)
    {
            $commonDomain = \NextDeveloper\Commons\Database\Models\Domains::where('uuid', $value)->first();

        if($commonDomain) {
            return $this->builder->where('common_domain_id', '=', $commonDomain->id);
        }
    }

        //  This is an alias function of commonDomain
    public function common_domain_id($value)
    {
        return $this->commonDomain($value);
    }
    
    public function commonCategoryId($value)
    {
            $commonCategory = \NextDeveloper\Commons\Database\Models\Categories::where('uuid', $value)->first();

        if($commonCategory) {
            return $this->builder->where('common_category_id', '=', $commonCategory->id);
        }
    }

        //  This is an alias function of commonCategory
    public function common_category_id($value)
    {
        return $this->commonCategory($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE






}
