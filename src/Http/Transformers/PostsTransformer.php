<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
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
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Posts', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
