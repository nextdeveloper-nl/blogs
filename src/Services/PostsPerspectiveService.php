<?php

namespace NextDeveloper\Blogs\Services;

use Illuminate\Support\Str;
use NextDeveloper\Blogs\Database\Filters\PostsPerspectiveQueryFilter;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractPostsPerspectiveService;

/**
 * This class is responsible from managing the data for PostsPerspective
 *
 * Class PostsPerspectiveService.
 *
 * @package NextDeveloper\Blogs\Database\Models
 */
class PostsPerspectiveService extends AbstractPostsPerspectiveService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function get(PostsPerspectiveQueryFilter $filter = null, array $params = []): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $list = parent::get($filter, $params);

        for($i = 0; $i < count($list); $i++) {
            $list[$i]->body = strip_tags($list[$i]->body);
            $list[$i]->body = Str::words($list[$i]->body, 50);
        }

        return $list;
    }
}
