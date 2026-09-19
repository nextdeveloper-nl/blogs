<?php

namespace NextDeveloper\Blogs\Http\Transformers\AbstractTransformers;

use NextDeveloper\Commons\Database\Models\Addresses;
use NextDeveloper\Commons\Database\Models\Comments;
use NextDeveloper\Commons\Database\Models\Meta;
use NextDeveloper\Commons\Database\Models\PhoneNumbers;
use NextDeveloper\Commons\Database\Models\SocialMedia;
use NextDeveloper\Commons\Database\Models\Votes;
use NextDeveloper\Commons\Database\Models\Media;
use NextDeveloper\Commons\Http\Transformers\MediaTransformer;
use NextDeveloper\Commons\Database\Models\AvailableActions;
use NextDeveloper\Commons\Http\Transformers\AvailableActionsTransformer;
use NextDeveloper\Commons\Database\Models\States;
use NextDeveloper\Commons\Http\Transformers\StatesTransformer;
use NextDeveloper\Commons\Http\Transformers\CommentsTransformer;
use NextDeveloper\Commons\Http\Transformers\SocialMediaTransformer;
use NextDeveloper\Commons\Http\Transformers\MetaTransformer;
use NextDeveloper\Commons\Http\Transformers\VotesTransformer;
use NextDeveloper\Commons\Http\Transformers\AddressesTransformer;
use NextDeveloper\Commons\Http\Transformers\PhoneNumbersTransformer;
use NextDeveloper\Blogs\Database\Models\ContentRequestItems;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Class ContentRequestItemsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class AbstractContentRequestItemsTransformer extends AbstractTransformer
{

    /**
     * @var array
     */
    protected array $availableIncludes = [
        'states',
        'actions',
        'media',
        'comments',
        'votes',
        'socialMedia',
        'phoneNumbers',
        'addresses',
        'meta'
    ];

    /**
     * @param ContentRequestItems $model
     *
     * @return array
     */
    public function transform(ContentRequestItems $model)
    {
                                                $blogContentRequestId = \NextDeveloper\Blogs\Database\Models\ContentRequests::where('id', $model->blog_content_request_id)->first();
                                                            $blogPostId = \NextDeveloper\Blogs\Database\Models\Posts::where('id', $model->blog_post_id)->first();
                                                            $commonCategoryId = \NextDeveloper\Commons\Database\Models\Categories::where('id', $model->common_category_id)->first();
                                                            $commonDomainId = \NextDeveloper\Commons\Database\Models\Domains::where('id', $model->common_domain_id)->first();
                                                            $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                                                            $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'blog_content_request_id'  =>  $blogContentRequestId ? $blogContentRequestId->uuid : null,
            'status'  =>  $model->status,
            'position'  =>  $model->position,
            'blog_post_id'  =>  $blogPostId ? $blogPostId->uuid : null,
            'find_text'  =>  $model->find_text,
            'replace_html'  =>  $model->replace_html,
            'target_url'  =>  $model->target_url,
            'anchor_text'  =>  $model->anchor_text,
            'extra_sentence'  =>  $model->extra_sentence,
            'rel'  =>  $model->rel,
            'match_count'  =>  $model->match_count,
            'submitted_title'  =>  $model->submitted_title,
            'submitted_body'  =>  $model->submitted_body,
            'submitted_abstract'  =>  $model->submitted_abstract,
            'submitted_meta_title'  =>  $model->submitted_meta_title,
            'submitted_meta_description'  =>  $model->submitted_meta_description,
            'submitted_author'  =>  $model->submitted_author,
            'is_markdown'  =>  $model->is_markdown,
            'common_category_id'  =>  $commonCategoryId ? $commonCategoryId->uuid : null,
            'common_domain_id'  =>  $commonDomainId ? $commonDomainId->uuid : null,
            'locale'  =>  $model->locale,
            'brief_title'  =>  $model->brief_title,
            'brief'  =>  $model->brief,
            'brief_keywords'  =>  $model->brief_keywords,
            'brief_audience'  =>  $model->brief_audience,
            'brief_outline'  =>  $model->brief_outline,
            'required_links'  =>  $model->required_links,
            'writer_article_ref'  =>  $model->writer_article_ref,
            'writer_stage'  =>  $model->writer_stage,
            'delivered_body'  =>  $model->delivered_body,
            'delivered_format'  =>  $model->delivered_format,
            'delivered_at'  =>  $model->delivered_at,
            'original_snapshot'  =>  $model->original_snapshot,
            'result'  =>  $model->result,
            'applied_at'  =>  $model->applied_at,
            'reverted_at'  =>  $model->reverted_at,
            'error_message'  =>  $model->error_message,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    public function includeStates(ContentRequestItems $model)
    {
        $states = States::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($states, new StatesTransformer());
    }

    public function includeActions(ContentRequestItems $model)
    {
        $input = get_class($model);
        $input = str_replace('\\Database\\Models', '', $input);

        $actions = AvailableActions::withoutGlobalScope(AuthorizationScope::class)
            ->where('input', $input)
            ->get();

        return $this->collection($actions, new AvailableActionsTransformer());
    }

    public function includeMedia(ContentRequestItems $model)
    {
        $media = Media::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($media, new MediaTransformer());
    }

    public function includeSocialMedia(ContentRequestItems $model)
    {
        $socialMedia = SocialMedia::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($socialMedia, new SocialMediaTransformer());
    }

    public function includeComments(ContentRequestItems $model)
    {
        $comments = Comments::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($comments, new CommentsTransformer());
    }

    public function includeVotes(ContentRequestItems $model)
    {
        $votes = Votes::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($votes, new VotesTransformer());
    }

    public function includeMeta(ContentRequestItems $model)
    {
        $meta = Meta::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($meta, new MetaTransformer());
    }

    public function includePhoneNumbers(ContentRequestItems $model)
    {
        $phoneNumbers = PhoneNumbers::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($phoneNumbers, new PhoneNumbersTransformer());
    }

    public function includeAddresses(ContentRequestItems $model)
    {
        $addresses = Addresses::where('object_type', get_class($model))
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($addresses, new AddressesTransformer());
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
