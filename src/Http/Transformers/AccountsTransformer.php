<?php

namespace NextDeveloper\Blogs\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Blogs\Http\Transformers\AbstractTransformers\AbstractAccountsTransformer;

/**
 * Class AccountsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Blogs\Http\Transformers
 */
class AccountsTransformer extends AbstractAccountsTransformer
{

    /**
     * @param Accounts $model
     *
     * @return array
     */
    public function transform(Accounts $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Accounts', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Accounts', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
