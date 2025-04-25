<?php

namespace Aesis\Likeable;

use Composer\InstalledVersions;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class LikeableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();
        $this->registerAbout();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/likeable.php', 'likeable');
    }

    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

    protected function offerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/likeable.php' => config_path('likeable.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/create_likeable_table.php' => $this->getMigrationFileName('create_likeable_table.php'),
            __DIR__ . '/../migrations/create_dislikeable_table.php' => $this->getMigrationFileName('create_dislikeable_table.php'),
        ], 'migrations');
    }

    protected function registerAbout(): void
    {
        if (! class_exists(InstalledVersions::class) || ! class_exists(AboutCommand::class)) {
            return;
        }

        AboutCommand::add('Laravel Likable', static fn () => [
            'Author' => 'Danila Mikhalev & TheZeroDay',
            'Version' => InstalledVersions::getPrettyVersion('curly-deni/laravel-likeable'),
        ]);
    }
}
