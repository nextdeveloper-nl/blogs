<?php

namespace NextDeveloper\Blogs\Services;

use Illuminate\Support\Str;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractPostsService;

/**
 * This class is responsible from managing the data for Posts
 *
 * Class PostsService.
 *
 * @package NextDeveloper\Blogs\Database\Models
 */
class PostsService extends AbstractPostsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Reduces the size of the content while taking the blogs as list.
     *
     * @param PostsQueryFilter|null $filter
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function get(PostsQueryFilter $filter = null, array $params = []): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $list = parent::get($filter, $params);

        for($i = 0; $i < count($list); $i++) {
            $list[$i]->body = strip_tags($list[$i]->body);
            $list[$i]->body = Str::words($list[$i]->body, 50);
        }

        return $list;
    }

    public static function create($data)
    {
        if(is_array($data['tags'])) {
            foreach ($data['tags'] as &$tag)
                $tag = Str::replace(',', '', $tag);
        }

        return parent::create($data);
    }
}
