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
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    public function register()
    {
    }
}