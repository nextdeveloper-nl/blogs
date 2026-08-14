<?php

namespace NextDeveloper\Blogs\Console\Commands;

use Illuminate\Console\Command;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Backfills the alternates column for existing translation groups. Historically
 * only the root post's alternates field was ever populated by TranslatePost /
 * UpdatePostTranslations, leaving every translated post with an empty
 * alternates column even though it belongs to a group.
 */
class SyncPostAlternatesCommand extends Command
{
    protected $signature = 'nextdeveloper:blogs:sync-post-alternates';

    protected $description = 'Rebuild the alternates column for every post translation group so each member lists all other members';

    public function handle(): void
    {
        $rootIds = Posts::withoutGlobalScope(AuthorizationScope::class)
            ->whereNull('alternate_of')
            ->pluck('id');

        $this->info("Checking {$rootIds->count()} root post(s) for translation groups...");

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
