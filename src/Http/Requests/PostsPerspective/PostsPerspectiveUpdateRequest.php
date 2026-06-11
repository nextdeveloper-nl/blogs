<?php

namespace NextDeveloper\Blogs\Http\Requests\PostsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class PostsPerspectiveUpdateRequest extends AbstractFormRequest
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
        'abstract' => 'nullable|string',
        'header_image' => 'nullable|string',
        'meta_title' => 'nullable|string',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'reply_count' => 'nullable|integer',
        'read_count' => 'nullable|integer',
        'bonus_points' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
        'is_locked' => 'nullable|boolean',
        'is_pinned' => 'nullable|boolean',
        'is_draft' => 'nullable|boolean',
        'is_markdown' => 'nullable|boolean',
        'tags' => 'nullable',
        'common_domain_id' => 'nullable|exists:common_domains,uuid|uuid',
        'locale' => 'nullable|string',
        'alternates' => 'nullable',
        'alternate_of' => 'nullable|integer',
        'faqs' => 'nullable',
        'author' => 'nullable|string',
        'team' => 'nullable|string',
        'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'category' => 'nullable|string',
        'domain_name' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}