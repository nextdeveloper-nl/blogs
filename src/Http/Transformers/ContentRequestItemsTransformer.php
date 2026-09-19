<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\ContentRequestItems;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractContentRequestItemsTransformer;

/**
 * Class ContentRequestItemsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class ContentRequestItemsTransformer extends AbstractContentRequestItemsTransformer
{

    /**
     * @param ContentRequestItems $model
     *
     * @return array
     */
    public function transform(ContentRequestItems $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('ContentRequestItems', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('ContentRequestItems', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
