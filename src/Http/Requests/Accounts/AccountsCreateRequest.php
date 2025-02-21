<?php

namespace NextDeveloper\Blogs\Http\Requests\Accounts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'common_domain_id' => 'required|exists:common_domains,uuid|uuid',
        'alternate' => '',
        'is_auto_translate_enabled' => 'boolean',
        'limits' => '',
        'is_suspended' => 'boolean',
        'common_language_id' => 'nullable|exists:common_languages,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}