<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\PostSlugHistories;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractPostSlugHistoriesTransformer;

/**
 * Class PostSlugHistoriesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class PostSlugHistoriesTransformer extends AbstractPostSlugHistoriesTransformer
{

    /**
     * @param PostSlugHistories $model
     *
     * @return array
     */
    public function transform(PostSlugHistories $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('PostSlugHistories', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('PostSlugHistories', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
