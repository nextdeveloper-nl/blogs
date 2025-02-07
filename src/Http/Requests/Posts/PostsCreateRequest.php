<?php

namespace NextDeveloper\Blogs\Http\Requests\Posts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class PostsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => 'nullable|string',
        'title' => 'required|string',
        'body' => 'required|string',
        'header_image' => 'nullable|string',
        'meta_title' => 'nullable|string',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'is_pinned' => 'boolean',
        'is_draft' => 'boolean',
        'is_markdown' => 'boolean',
        'tags' => '',
        'common_category_id' => 'required|exists:common_categories,uuid|uuid',
        'common_domain_id' => 'required|exists:common_domains,uuid|uuid',
        'abstract' => 'nullable|string',
        'alternates' => '',
        'alternate_of' => 'nullable|integer',
        'locale' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
