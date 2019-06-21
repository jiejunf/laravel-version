<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: jiejun
 * Date: 2019/4/17
 * Time: 下午12:08
 */

return [
    /* ======================
     * app version config
     * ======================
     */

    'platform' => ['android', 'ios'],

    // if equal NULL will remove table_column(app_type),and {type} be replaced with 'app' in route
    // such as `versions/customer/android/latest` -> `versions/app/android/latest`
    //                   ^^^^^^^^                              ^^^
    'app_type' => ['worker', 'customer'],

    'app_route_middleware' => [],

    'app_route_prefix' => 'api/versions',

    'app_controller_namespace' => '\Jiejunf\VersionService\Controllers',

    /* ======================
     * server version config
     * ======================
     * for gitlab to update code
     */
    'server_enable' => env('APP_DEBUG'),

    'server_route_middleware' => [],

    'server_route_prefix' => 'api/version',

    'server_controller_namespace' => '\Jiejunf\VersionService\Controllers',
];