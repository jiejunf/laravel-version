<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: jiejun
 * Date: 2019/4/17
 * Time: 上午11:10
 */

use Illuminate\Support\Facades\Route;

/**
 * App Version
 */
Route::group([
    'middleware' => config('version.app_route_middleware'),
    'prefix' => config('version.app_route_prefix'),
    'namespace' => config('version.app_controller_namespace')], function () {
    $configAppType = config('version.app_type');
    $routeWhere = ['platform' => join('|', config('version.platform'))]
        + ($configAppType ? ['type' => join('|', $configAppType)] : []);
    $routeType = $configAppType ? '{type}' : '';
    // 获取最新版本
    Route::get($routeType . '/{platform}/latest', 'AppVersionController@getLatestVersion')->where($routeWhere);
    // 获取版本列表
    Route::get($routeType . '/{platform}', 'AppVersionController@getVersionList')->where($routeWhere);
    // 更新版本
    Route::put('/', 'AppVersionController@updateOrCreateVersion');
});
/**
 * Server Version
 */
if (config('version.server_enable')) {
    Route::group([
        'middleware' => config('version.server_route_middleware'),
        'prefix' => config('version.server_route_prefix'),
        'namespace' => config('version.server_controller_namespace')], function () {
        // 获取服务器当前版本
        Route::get('/', 'ServerVersionController@getCurrentVersion');
        // 更新服务器代码（由gitlab webHook发起）
        Route::post('/update', 'ServerVersionController@updateVersion');
    });
}
