<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\AccountsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractAccountsPerspectiveTransformer;

/**
 * Class AccountsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class AccountsPerspectiveTransformer extends AbstractAccountsPerspectiveTransformer
{

    /**
     * @param AccountsPerspective $model
     *
     * @return array
     */
    public function transform(AccountsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
