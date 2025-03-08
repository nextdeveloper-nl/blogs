<?php

namespace NextDeveloper\Blogs;

use Illuminate\Support\Facades\Auth;
use NextDeveloper\Commons\AbstractServiceProvider;
use NextDeveloper\IAM\Auth\Providers\IamUserProvider;
use Illuminate\Console\Scheduling\Schedule;
/**
 * Class IAMServiceProvider
 *
 * @package NextDeveloper\IAM
 */
class BlogsServiceProvider extends AbstractServiceProvider {
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../config/blogs.php' => config_path('blogs.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Blogs');

        $this->bootGuard();
        $this->bootChannelRoutes();
    }

    public function bootGuard() {

    }

    /**
     * @return void
     */
    public function register() {
        $this->registerHelpers();
        $this->registerRoutes();
        $this->registerCommands();

        $this->mergeConfigFrom(__DIR__.'/../config/blogs.php', 'blogs');
        $this->customMergeConfigFrom(__DIR__.'/../config/relation.php', 'relation');
    }

    /**
     * @return array
     */
    public function provides() {
        return ['blogs'];
    }


    /**
     * @return void
     */
    private function bootChannelRoutes() {
        if (file_exists(($file = $this->dir.'/../config/channel.routes.php'))) {
            require_once $file;
        }
    }

    /**
     * Register module routes
     *
     * @return void
     */
    protected function registerRoutes() {
        if ( ! $this->app->routesAreCached() && config('leo.allowed_routes.blogs', true) ) {
            $this->app['router']
                ->namespace('NextDeveloper\Blogs\Http\Controllers')
                ->group(__DIR__.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'api.routes.php');
        }
    }

    /**
     * Registers module based commands
     * @return void
     */
    protected function registerCommands() {}

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    private function bootSchedule() {}
}
