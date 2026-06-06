<?php

namespace NextDeveloper\Blogs\Http\Requests\AccountsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountsPerspectiveUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'tags' => 'nullable',
        'common_domain_id' => 'nullable|exists:common_domains,uuid|uuid',
        'common_language_id' => 'nullable|exists:common_languages,uuid|uuid',
        'limits' => 'nullable',
        'is_suspended' => 'nullable|boolean',
        'is_auto_translate_enabled' => 'nullable|boolean',
        'alternate' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}