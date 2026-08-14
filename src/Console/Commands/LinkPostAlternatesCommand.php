<?php

namespace NextDeveloper\Blogs\Console\Commands;

use Illuminate\Console\Command;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Links pre-existing posts as alternates of each other from a manually
 * curated mapping file. Unlike TranslatePost/UpdatePostTranslations, these
 * posts were never created through the auto-translate flow, so there is no
 * alternate_of relationship to sync from — a human has to identify which
 * posts are actually translations of one another first.
 *
 * Input: a JSON file containing an array of groups, each group an array of
 * post IDs that are translations of each other, e.g.:
 *   [[32, 33], [108, 109, 110]]
 *
 * For each group, the earliest-created post becomes the root (alternate_of
 * set to null, matching the convention used elsewhere), every other post in
 * the group gets alternate_of pointed at the root, and PostsService::
 * syncAlternatesForGroup() rebuilds the alternates column on every member.
 */
class LinkPostAlternatesCommand extends Command
{
    protected $signature = 'nextdeveloper:blogs:link-post-alternates {file : Path to a JSON file containing groups of post IDs that are translations of each other, e.g. [[32, 33], [108, 109, 110]]}';

    protected $description = 'Links manually identified posts as alternates of each other and syncs the alternates column across each group';

    public function handle(): void
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");

            return;
        }

        $groups = json_decode(file_get_contents($path), true);

        if (!is_array($groups)) {
            $this->error('File must contain a JSON array of arrays of post IDs.');

            return;
        }

        foreach ($groups as $index => $ids) {
            if (!is_array($ids) || count($ids) < 2) {
                $this->warn("Skipping group #{$index}: needs at least 2 post IDs");

                continue;
            }

            $posts = Posts::withoutGlobalScope(AuthorizationScope::class)
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');

            $missing = array_diff($ids, $posts->keys()->toArray());

            if (!empty($missing)) {
                $this->warn("Skipping group #{$index}: post ID(s) not found: " . implode(', ', $missing));

                continue;
            }

            $root = $posts->sortBy('created_at')->first();

            foreach ($posts as $post) {
                if ($post->id === $root->id) {
                    if ($post->alternate_of !== null) {
                        $post->updateQuietly(['alternate_of' => null]);
                    }

                    continue;
                }

                if ($post->alternate_of !== $root->id) {
                    $post->updateQuietly(['alternate_of' => $root->id]);
                }
            }

            PostsService::syncAlternatesForGroup($root->id);

            $this->info("Linked group #{$index}: root={$root->id} (" . implode(', ', $ids) . ')');
        }

        $this->info('Done.');
    }
}
