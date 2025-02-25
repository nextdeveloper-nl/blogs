<?php

namespace NextDeveloper\Blogs\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function isAutoTranslateEnabled($value)
    {
        return $this->builder->where('is_auto_translate_enabled', $value);
    }

        //  This is an alias function of isAutoTranslateEnabled
    public function is_auto_translate_enabled($value)
    {
        return $this->isAutoTranslateEnabled($value);
    }
     
    public function isSuspended($value)
    {
        return $this->builder->where('is_suspended', $value);
    }

        //  This is an alias function of isSuspended
    public function is_suspended($value)
    {
        return $this->isSuspended($value);
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
    
    public function commonLanguageId($value)
    {
            $commonLanguage = \NextDeveloper\Commons\Database\Models\Languages::where('uuid', $value)->first();

        if($commonLanguage) {
            return $this->builder->where('common_language_id', '=', $commonLanguage->id);
        }
    }

        //  This is an alias function of commonLanguage
    public function common_language_id($value)
    {
        return $this->commonLanguage($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE















}
