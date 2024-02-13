<?php

namespace NextDeveloper\Blogs\Http\Transformers\AbstractTransformers;

use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class PostsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class AbstractPostsTransformer extends AbstractTransformer
{

    /**
     * @param Posts $model
     *
     * @return array
     */
    public function transform(Posts $model)
    {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                    $commonCategoryId = \NextDeveloper\Commons\Database\Models\Categories::where('id', $model->common_category_id)->first();
                    $commonDomainId = \NextDeveloper\Commons\Database\Models\Domains::where('id', $model->common_domain_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'slug'  =>  $model->slug,
            'title'  =>  $model->title,
            'body'  =>  $model->body,
            'header_image'  =>  $model->header_image,
            'meta_title'  =>  $model->meta_title,
            'meta_description'  =>  $model->meta_description,
            'meta_keywords'  =>  $model->meta_keywords,
            'reply_count'  =>  $model->reply_count,
            'read_count'  =>  $model->read_count,
            'bonus_points'  =>  $model->bonus_points,
            'is_active'  =>  $model->is_active,
            'is_locked'  =>  $model->is_locked,
            'is_pinned'  =>  $model->is_pinned,
            'is_draft'  =>  $model->is_draft,
            'is_markdown'  =>  $model->is_markdown,
            'tags'  =>  $model->tags,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'common_category_id'  =>  $commonCategoryId ? $commonCategoryId->uuid : null,
            'common_domain_id'  =>  $commonDomainId ? $commonDomainId->uuid : null,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
