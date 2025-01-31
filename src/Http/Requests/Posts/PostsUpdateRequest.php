<?php

namespace NextDeveloper\Blogs\Http\Requests\Posts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class PostsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => 'nullable|string',
        'title' => 'nullable|string',
        'body' => 'nullable|string',
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
        'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'common_domain_id' => 'nullable|exists:common_domains,uuid|uuid',
        'astract' => 'nullable|string',
        'alternates' => '',
        'alternate_of' => 'nullable|integer',
        'locale' => 'string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}