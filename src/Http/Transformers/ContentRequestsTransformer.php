<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\ContentRequests;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractContentRequestsTransformer;

/**
 * Class ContentRequestsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class ContentRequestsTransformer extends AbstractContentRequestsTransformer
{

    /**
     * @param ContentRequests $model
     *
     * @return array
     */
    public function transform(ContentRequests $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('ContentRequests', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('ContentRequests', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
