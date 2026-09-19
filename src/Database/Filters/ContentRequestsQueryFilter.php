<?php

namespace NextDeveloper\Blogs\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class ContentRequestsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function notes($value)
    {
        return $this->builder->where('notes', 'ilike', '%' . $value . '%');
    }

        
    public function rejectionReason($value)
    {
        return $this->builder->where('rejection_reason', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of rejectionReason
    public function rejection_reason($value)
    {
        return $this->rejectionReason($value);
    }
    
    public function quoteAmount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('quote_amount', $operator, $value);
    }

        //  This is an alias function of quoteAmount
    public function quote_amount($value)
    {
        return $this->quoteAmount($value);
    }
    
    public function requestedAtStart($date)
    {
        return $this->builder->where('requested_at', '>=', $date);
    }

    public function requestedAtEnd($date)
    {
        return $this->builder->where('requested_at', '<=', $date);
    }

    //  This is an alias function of requestedAt
    public function requested_at_start($value)
    {
        return $this->requestedAtStart($value);
    }

    //  This is an alias function of requestedAt
    public function requested_at_end($value)
    {
        return $this->requestedAtEnd($value);
    }

    public function submittedAtStart($date)
    {
        return $this->builder->where('submitted_at', '>=', $date);
    }

    public function submittedAtEnd($date)
    {
        return $this->builder->where('submitted_at', '<=', $date);
    }

    //  This is an alias function of submittedAt
    public function submitted_at_start($value)
    {
        return $this->submittedAtStart($value);
    }

    //  This is an alias function of submittedAt
    public function submitted_at_end($value)
    {
        return $this->submittedAtEnd($value);
    }

    public function reviewedAtStart($date)
    {
        return $this->builder->where('reviewed_at', '>=', $date);
    }

    public function reviewedAtEnd($date)
    {
        return $this->builder->where('reviewed_at', '<=', $date);
    }

    //  This is an alias function of reviewedAt
    public function reviewed_at_start($value)
    {
        return $this->reviewedAtStart($value);
    }

    //  This is an alias function of reviewedAt
    public function reviewed_at_end($value)
    {
        return $this->reviewedAtEnd($value);
    }

    public function completedAtStart($date)
    {
        return $this->builder->where('completed_at', '>=', $date);
    }

    public function completedAtEnd($date)
    {
        return $this->builder->where('completed_at', '<=', $date);
    }

    //  This is an alias function of completedAt
    public function completed_at_start($value)
    {
        return $this->completedAtStart($value);
    }

    //  This is an alias function of completedAt
    public function completed_at_end($value)
    {
        return $this->completedAtEnd($value);
    }

    public function quotedAtStart($date)
    {
        return $this->builder->where('quoted_at', '>=', $date);
    }

    public function quotedAtEnd($date)
    {
        return $this->builder->where('quoted_at', '<=', $date);
    }

    //  This is an alias function of quotedAt
    public function quoted_at_start($value)
    {
        return $this->quotedAtStart($value);
    }

    //  This is an alias function of quotedAt
    public function quoted_at_end($value)
    {
        return $this->quotedAtEnd($value);
    }

    public function acceptedAtStart($date)
    {
        return $this->builder->where('accepted_at', '>=', $date);
    }

    public function acceptedAtEnd($date)
    {
        return $this->builder->where('accepted_at', '<=', $date);
    }

    //  This is an alias function of acceptedAt
    public function accepted_at_start($value)
    {
        return $this->acceptedAtStart($value);
    }

    //  This is an alias function of acceptedAt
    public function accepted_at_end($value)
    {
        return $this->acceptedAtEnd($value);
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

    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

        //  This is an alias function of crmAccount
    public function crm_account_id($value)
    {
        return $this->crmAccount($value);
    }
    
    public function reviewerUserId($value)
    {
            $reviewerUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($reviewerUser) {
            return $this->builder->where('reviewer_user_id', '=', $reviewerUser->id);
        }
    }

        //  This is an alias function of reviewerUser
    public function reviewer_user_id($value)
    {
        return $this->reviewerUser($value);
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

    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
