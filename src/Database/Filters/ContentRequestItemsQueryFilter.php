<?php

namespace NextDeveloper\Blogs\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class ContentRequestItemsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function findText($value)
    {
        return $this->builder->where('find_text', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of findText
    public function find_text($value)
    {
        return $this->findText($value);
    }
        
    public function replaceHtml($value)
    {
        return $this->builder->where('replace_html', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of replaceHtml
    public function replace_html($value)
    {
        return $this->replaceHtml($value);
    }
        
    public function extraSentence($value)
    {
        return $this->builder->where('extra_sentence', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of extraSentence
    public function extra_sentence($value)
    {
        return $this->extraSentence($value);
    }
        
    public function submittedBody($value)
    {
        return $this->builder->where('submitted_body', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of submittedBody
    public function submitted_body($value)
    {
        return $this->submittedBody($value);
    }
        
    public function submittedAbstract($value)
    {
        return $this->builder->where('submitted_abstract', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of submittedAbstract
    public function submitted_abstract($value)
    {
        return $this->submittedAbstract($value);
    }
        
    public function submittedMetaDescription($value)
    {
        return $this->builder->where('submitted_meta_description', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of submittedMetaDescription
    public function submitted_meta_description($value)
    {
        return $this->submittedMetaDescription($value);
    }
        
    public function brief($value)
    {
        return $this->builder->where('brief', 'ilike', '%' . $value . '%');
    }

        
    public function deliveredBody($value)
    {
        return $this->builder->where('delivered_body', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of deliveredBody
    public function delivered_body($value)
    {
        return $this->deliveredBody($value);
    }
        
    public function originalSnapshot($value)
    {
        return $this->builder->where('original_snapshot', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of originalSnapshot
    public function original_snapshot($value)
    {
        return $this->originalSnapshot($value);
    }
        
    public function errorMessage($value)
    {
        return $this->builder->where('error_message', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of errorMessage
    public function error_message($value)
    {
        return $this->errorMessage($value);
    }
    
    public function position($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('position', $operator, $value);
    }

    
    public function matchCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('match_count', $operator, $value);
    }

        //  This is an alias function of matchCount
    public function match_count($value)
    {
        return $this->matchCount($value);
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
     
    public function deliveredAtStart($date)
    {
        return $this->builder->where('delivered_at', '>=', $date);
    }

    public function deliveredAtEnd($date)
    {
        return $this->builder->where('delivered_at', '<=', $date);
    }

    //  This is an alias function of deliveredAt
    public function delivered_at_start($value)
    {
        return $this->deliveredAtStart($value);
    }

    //  This is an alias function of deliveredAt
    public function delivered_at_end($value)
    {
        return $this->deliveredAtEnd($value);
    }

    public function appliedAtStart($date)
    {
        return $this->builder->where('applied_at', '>=', $date);
    }

    public function appliedAtEnd($date)
    {
        return $this->builder->where('applied_at', '<=', $date);
    }

    //  This is an alias function of appliedAt
    public function applied_at_start($value)
    {
        return $this->appliedAtStart($value);
    }

    //  This is an alias function of appliedAt
    public function applied_at_end($value)
    {
        return $this->appliedAtEnd($value);
    }

    public function revertedAtStart($date)
    {
        return $this->builder->where('reverted_at', '>=', $date);
    }

    public function revertedAtEnd($date)
    {
        return $this->builder->where('reverted_at', '<=', $date);
    }

    //  This is an alias function of revertedAt
    public function reverted_at_start($value)
    {
        return $this->revertedAtStart($value);
    }

    //  This is an alias function of revertedAt
    public function reverted_at_end($value)
    {
        return $this->revertedAtEnd($value);
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

    public function blogContentRequestId($value)
    {
            $blogContentRequest = \NextDeveloper\Blogs\Database\Models\ContentRequests::where('uuid', $value)->first();

        if($blogContentRequest) {
            return $this->builder->where('blog_content_request_id', '=', $blogContentRequest->id);
        }
    }

        //  This is an alias function of blogContentRequest
    public function blog_content_request_id($value)
    {
        return $this->blogContentRequest($value);
    }
    
    public function blogPostId($value)
    {
            $blogPost = \NextDeveloper\Blogs\Database\Models\Posts::where('uuid', $value)->first();

        if($blogPost) {
            return $this->builder->where('blog_post_id', '=', $blogPost->id);
        }
    }

        //  This is an alias function of blogPost
    public function blog_post_id($value)
    {
        return $this->blogPost($value);
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
