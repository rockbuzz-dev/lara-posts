<?php

namespace Rockbuzz\LaraPosts;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $projectPath = database_path('migrations') . '/';
        $localPath = __DIR__ . '/database/migrations/';

        if (! $this->hasMigrationInProject($projectPath, $filesystem)) {
            $this->loadMigrationsFrom($localPath . '2020_09_23_000000_create_posts_table.php');

            $this->publishes([
                $localPath . '2020_09_23_000000_create_posts_table.php' =>
                    $projectPath . now()->format('Y_m_d_his') . '_create_posts_table.php'
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/config/posts.php' => config_path('posts.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/posts.php', 'posts');
    }

    private function hasMigrationInProject(string $path, Filesystem $filesystem)
    {
        return count($filesystem->glob($path . '*_create_posts_table.php')) > 0;
    }
}
