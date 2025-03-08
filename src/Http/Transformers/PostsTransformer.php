<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractPostsTransformer;

/**
 * Class PostsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class PostsTransformer extends AbstractPostsTransformer
{
    protected array $availableIncludes = [
        'states',
        'actions',
        'media',
        'comments',
        'votes',
        'socialMedia',
        'phoneNumbers',
        'addresses',
        'meta',
        'user'
    ];

    protected array $defaultIncludes = [
        'user'
    ];


    /**
     * @param Posts $model
     *
     * @return array
     */
    public function transform(Posts $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Posts', $model->uuid, 'Transformed')
        );

        if($transformed) {
//            return $transformed;
        }

        $transformed = parent::transform($model);

        $transformed['alternate_posts'] = PostsService::getAlternates($model);
        unset($transformed['alternates']);

        Cache::set(
            CacheHelper::getKey('Posts', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }

    public function includeUser(Posts $model) {
        $user = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();

        return $this->item($user, new \NextDeveloper\IAM\Http\Transformers\PublicUsersTransformer());
    }
}
