<?php

namespace NextDeveloper\Blogs\Http\Requests\ContentRequestItems;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ContentRequestItemsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'blog_content_request_id' => 'required|exists:blog_content_requests,uuid|uuid',
        'position' => 'integer',
        'blog_post_id' => 'nullable|exists:blog_posts,uuid|uuid',
        'find_text' => 'nullable|string',
        'replace_html' => 'nullable|string',
        'target_url' => 'nullable',
        'anchor_text' => 'nullable',
        'extra_sentence' => 'nullable|string',
        'rel' => '',
        'submitted_title' => 'nullable',
        'submitted_body' => 'nullable|string',
        'submitted_abstract' => 'nullable|string',
        'submitted_meta_title' => 'nullable',
        'submitted_meta_description' => 'nullable|string',
        'submitted_author' => 'nullable',
        'is_markdown' => 'boolean',
        'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'common_domain_id' => 'nullable|exists:common_domains,uuid|uuid',
        'locale' => 'nullable',
        'brief_title' => 'nullable',
        'brief' => 'nullable|string',
        'brief_keywords' => 'nullable',
        'brief_audience' => 'nullable',
        'brief_outline' => 'nullable',
        'required_links' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}