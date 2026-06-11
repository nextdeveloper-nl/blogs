<?php

namespace NextDeveloper\Blogs\Services;

use Google\Service\Blogger\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Database\Models\PostSlugHistories;
use NextDeveloper\Blogs\Database\Models\PostsPerspective;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractPostsService;
use NextDeveloper\Commons\Database\Models\Domains;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This class is responsible from managing the data for Posts
 *
 * Class PostsService.
 */
class PostsService extends AbstractPostsService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function update($id, array $data)
    {
        $post = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $id)
            ->first();

        $oldSlug = $post?->slug;

        $updated = parent::update($id, $data);

        if ($updated && $oldSlug && $updated->slug !== $oldSlug) {
            UserHelper::runAsAdmin(function () use ($updated, $oldSlug) {
                PostSlugHistories::firstOrCreate([
                    'blog_post_id' => $updated->id,
                    'slug' => $oldSlug,
                ], [
                    'iam_account_id' => $updated->iam_account_id,
                    'iam_user_id' => $updated->iam_user_id,
                ]);

                PostSlugHistories::where('slug', $updated->slug)->delete();
            });
        }

        return $updated;
    }

    /**
     * Reduces the size of the content while taking the blogs as list.
     */
    public static function get(?PostsQueryFilter $filter = null, array $params = []): Collection|LengthAwarePaginator
    {
        $list = parent::get($filter, $params);

        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->body = strip_tags($list[$i]->body);
            $list[$i]->body = Str::words($list[$i]->body, 50);
        }

        return $list;
    }

    public static function create($data)
    {
        if (is_array($data['tags'])) {
            foreach ($data['tags'] as &$tag) {
                $tag = Str::replace(',', '', $tag);
            }
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

    /**
     * A post can either have alternates, or a parent blog that has alternates. Therefore we first need to
     * look at alternates. If the alternates are empty, then we go to alternateOf and build the alternates
     * information
     */
    public static function getAlternates(Posts|PostsPerspective $post): array
    {
        $alternates = $post->alternates;

        if (! $alternates) {
            $alternates = self::getParentAlternates($post);
        }

        $alternatedPosts = [];

        $parentBlog = self::getParentBlog($post);

        if (! $parentBlog && ! $alternates) {
            return [];
        }

        if ($parentBlog) {
            $alternatedPosts[] = [
                'uuid' => $parentBlog->uuid,
                'locale' => $parentBlog->locale,
                'title' => $parentBlog->title,
                'slug' => $parentBlog->slug,
            ];
        } else {
            $alternatedPosts[] = [
                'uuid' => $post->uuid,
                'locale' => $post->locale,
                'title' => $post->title,
                'slug' => $post->slug,
            ];
        }

        foreach ($alternates as $alternate) {
            $alternatePost = Posts::withoutGlobalScope(AuthorizationScope::class)
                ->where('slug', $alternate['slug'])
                ->first();

            if (! $alternatePost) {
                continue;
            }

            $alternatedPosts[] = [
                'uuid' => $alternatePost->uuid,
                'locale' => $alternatePost->locale,
                'title' => $alternatePost->title,
                'slug' => $alternatePost->slug,
            ];
        }

        return $alternatedPosts;
    }

    public static function getParentBlog(Posts|PostsPerspective $post): ?Posts
    {
        return Posts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $post->alternate_of)
            ->first();
    }

    public static function getParentAlternates(Posts|PostsPerspective $post): array
    {
        $parentBlog = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $post->alternate_of)
            ->first();

        if ($parentBlog) {
            return $parentBlog->alternates;
        }

        return [];
    }
}
