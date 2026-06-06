<?php

namespace NextDeveloper\Blogs\Http\Requests\PostSlugHistories;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class PostSlugHistoriesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'blog_post_id' => 'nullable|exists:blog_posts,uuid|uuid',
        'slug' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}