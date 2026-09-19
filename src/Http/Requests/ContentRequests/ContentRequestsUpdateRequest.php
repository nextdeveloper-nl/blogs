<?php

namespace NextDeveloper\Blogs\Http\Requests\ContentRequests;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ContentRequestsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'nullable',
        'title' => 'nullable',
        'partner_name' => 'nullable',
        'partner_email' => 'nullable',
        'partner_domain' => 'nullable',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'notes' => 'nullable|string',
        'source_reference' => 'nullable',
        'requested_at' => 'nullable|date',
        'rejection_reason' => 'nullable|string',
        'quote_amount' => 'nullable|numeric',
        'quote_currency_code' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}