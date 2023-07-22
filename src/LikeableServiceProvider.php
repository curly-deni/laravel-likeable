<?php

namespace Zeroday\Likeable;

use Illuminate\Support\ServiceProvider;

/**
 * Copyright (C) 2023 0Day
 */
class LikeableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $migration = '2023_03_20_000000_create_likeable_table.php';
        $this->publishes([
            __DIR__ . '/../config/likeable.php'      => config_path('likeable.php'),
            __DIR__ . '/../migrations/' . $migration => database_path('migrations/' . $migration),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/likeable.php', 'likeable');
    }
}