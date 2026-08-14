<?php

namespace NextDeveloper\Blogs\Console\Commands;

use Illuminate\Console\Command;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Backfills the alternates column for broken translation groups. Historically
 * only the root post's alternates field was ever populated by TranslatePost /
 * UpdatePostTranslations, leaving translated posts (alternate_of set) with an
 * empty alternates column even though they belong to a group.
 *
 * A post is considered broken when it has alternate_of set (it's a
 * translation of some root post) but its own alternates column is empty. For
 * every such post we resolve its root (alternate_of) and rebuild the
 * alternates column on every member of that root's group in one pass.
 */
class SyncPostAlternatesCommand extends Command
{
    protected $signature = 'nextdeveloper:blogs:sync-post-alternates';

    protected $description = 'Rebuild the alternates column for translation groups where an alternate post has alternate_of set but an empty alternates column';

    public function handle(): void
    {
        $rootIds = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->whereNotNull('alternate_of')
            ->where(function ($query) {
                $query->whereNull('alternates')
                    ->orWhereRaw("alternates::text = '[]'");
            })
            ->distinct()
            ->pluck('alternate_of');

        $this->info("Found {$rootIds->count()} translation group(s) with a broken alternate...");

        $bar = $this->output->createProgressBar($rootIds->count());
        $bar->start();

        foreach ($rootIds as $rootId) {
            PostsService::syncAlternatesForGroup($rootId);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done.');
    }
}
