<?php

namespace NextDeveloper\Blogs\Actions\Accounts;

use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Exceptions\NotAllowedException;

/**
 * This class handles lifting the suspension of a Blog account.
 */
class UnsuspendAccount extends AbstractAction
{
    public const EVENTS = [
        'unsuspended:NextDeveloper\Blogs\Accounts',
    ];

    /**
     * @throws NotAllowedException
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
        parent::__construct();
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Starting to unsuspend account');

        $this->model->updateQuietly([
            'is_suspended' => false,
        ]);

        CacheHelper::deleteKeys(get_class($this->model), $this->model->uuid);

        $this->setProgress(100, 'Account unsuspended');
    }
}
