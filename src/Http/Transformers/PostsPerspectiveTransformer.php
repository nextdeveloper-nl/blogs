<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\PostsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractPostsPerspectiveTransformer;

/**
 * Class PostsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class PostsPerspectiveTransformer extends AbstractPostsPerspectiveTransformer
{

    /**
     * @param PostsPerspective $model
     *
     * @return array
     */
    public function transform(PostsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('PostsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        $transformed['alternate_posts'] = PostsService::getAlternates($model);
        unset($transformed['alternates']);

        Cache::set(
            CacheHelper::getKey('PostsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
