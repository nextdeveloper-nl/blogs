<?php

namespace NextDeveloper\Blogs\Services;

use Google\Service\Blogger\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Database\Models\Accounts;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Database\Models\PostSlugHistories;
use NextDeveloper\Blogs\Database\Models\PostsPerspective;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractPostsService;
use NextDeveloper\Commons\Database\Models\Categories;
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

    /**
     * Turns a common_category_id uuid into the internal id, accepting only a category the caller may use.
     *
     * Done here, not in the abstract service: that one looks the category up through the AuthorizationScope, and on
     * a POST/PATCH the Commons role scopes common_categories to the caller's own account, so a platform (or public)
     * category came back null and the insert failed on the NOT NULL column (HTTP 500). Request validation already
     * proved the uuid exists; here we accept a system category (no owner), a public one, or one of the caller's own
     * account, and answer 422 naming the field otherwise.
     */
    private static function resolveCategory(array $data): array
    {
        if (! isset($data['common_category_id']) || ! is_string($data['common_category_id'])) {
            return $data;
        }

        $category = Categories::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $data['common_category_id'])
            ->first();

        $usable = $category
            && ($category->iam_account_id === null
                || $category->is_public
                || (int) $category->iam_account_id === (int) UserHelper::currentAccount()->id);

        if (! $usable) {
            throw ValidationException::withMessages(['common_category_id' => 'The selected common category id is invalid.']);
        }

        //  The abstract service passes an integer id through unchanged.
        $data['common_category_id'] = $category->id;

        return $data;
    }

    public static function update($id, array $data)
    {
        $data = self::resolveCategory($data);

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

        //  An unknown domain used to end in a null dereference (HTTP 500); answer 422 naming the field instead.
        if (! $domain) {
            throw ValidationException::withMessages(['common_domain_id' => 'The selected common domain id is invalid.']);
        }

        $account = Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('common_domain_id', $domain->id)
            ->first();

        if (! $account) {
            throw ValidationException::withMessages(['common_domain_id' => 'There is no blog account for this domain.']);
        }

        $data['blog_account_id'] = $account->id;

        $data = self::resolveCategory($data);

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

    /**
     * Rebuilds the alternates column for every post in a translation group (the
     * root post plus every post whose alternate_of points at it) so each member
     * lists every other member, not just the root. Historically only the root
     * post's alternates column was ever written, leaving translated posts with
     * an empty alternates field; this is used both to fix that going forward
     * (TranslatePost, UpdatePostTranslations) and to backfill existing rows.
     */
    public static function syncAlternatesForGroup(int $rootPostId): void
    {
        $group = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $rootPostId)
            ->orWhere('alternate_of', $rootPostId)
            ->get();

        if ($group->count() < 2) {
            return;
        }

        $entries = $group->map(fn (Posts $post) => [
            'id' => $post->id,
            'locale' => strtolower(trim((string) $post->locale)),
            'title' => $post->title,
            'slug' => $post->slug,
        ]);

        foreach ($group as $post) {
            $ownAlternates = $entries
                ->reject(fn (array $entry) => $entry['id'] === $post->id)
                ->values()
                ->toArray();

            if ($post->alternates !== $ownAlternates) {
                $post->updateQuietly(['alternates' => $ownAlternates]);
            }
        }
    }
}
