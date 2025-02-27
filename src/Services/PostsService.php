<?php

namespace NextDeveloper\Blogs\Services;

use Illuminate\Support\Str;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Database\Models\PostsPerspective;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractPostsService;
use NextDeveloper\Commons\Database\Models\Domains;
use NextDeveloper\Commons\Services\DomainsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

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

        $domain = Domains::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $data['common_domain_id'])
            ->first();

        $account = Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('common_domain_id', $domain->id)
            ->first();

        $data['blog_account_id'] = $account->id;

        return parent::create($data);
    }

    public static function getAlternates(Posts|PostsPerspective $post) : array
    {
        $alternates = $post->alternates;

        if($alternates) {
            $alternates[] = [
                'uuid'  =>  $post->uuid,
                'locale'    => $post->locale,
                'title' => $post->title,
                'slug'  => $post->slug
            ];

            return $alternates;
        }

        //  If the blog itself is the alternate of another blog, we need to get the alternates of the parent blog

        $alternateOf = $post->alternate_of;

        if($alternateOf) {
            $parentBlog = Posts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $alternateOf)
                ->first();

            $alternates = $parentBlog->alternates;

            $alternates[] = [
                'uuid'  => $parentBlog->uuid,
                'locale' => $parentBlog->locale,
                'title' => $parentBlog->title,
                'slug' => $parentBlog->slug
            ];
        }

        return $alternates;
    }
}
