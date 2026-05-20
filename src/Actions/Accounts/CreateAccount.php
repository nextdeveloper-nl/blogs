<?php

namespace NextDeveloper\Blogs\Actions\Accounts;

use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;

/**
 * Backfills a Blog account for an existing IAM account.
 *
 * The DB trigger creates child rows suspended; this action is used to manually
 * create a child row for historical IAM accounts and flags it not suspended.
 *
 * The IAM account must have a common_domain_id since blog_accounts requires it.
 */
class CreateAccount extends AbstractAction
{
    public const EVENTS = [
        'created:NextDeveloper\Blogs\Accounts',
    ];

    /**
     * @throws NotAllowedException
     */
    public function __construct(IamAccounts $iamAccount)
    {
        $this->model = $iamAccount;
        parent::__construct();
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Starting to create blog account');

        if ($this->model->common_domain_id === null) {
            $this->setProgress(100, 'Skipped: IAM account has no common_domain_id');

            return;
        }

        Accounts::withoutGlobalScopes()->firstOrCreate(
            ['iam_account_id' => $this->model->id],
            [
                'common_domain_id' => $this->model->common_domain_id,
                'is_suspended' => false,
            ]
        );

        $this->setProgress(100, 'Blog account created');
    }
}
