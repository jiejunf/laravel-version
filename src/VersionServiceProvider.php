<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: jiejun
 * Date: 2019/4/17
 * Time: 上午11:01
 */

namespace Jiejunf\VersionService;


use Illuminate\Support\ServiceProvider;

/**
 * Class AppVersionProvider
 * @package App\Providers\src
 *
 * ==============
 * run before use:
 * 0.register this provider at config/app.providers
 * 1.php artisan vendor:publish --provider="Jiejunf\VersionService\VersionServiceProvider"
 * 2.update config/version.php
 * 3.php artisan migrate
 * ==============
 */
class VersionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/version.php' => config_path('version.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . '/Routes/route.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/version.php', 'version'
        );
    }
}