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

    // if app_type is empty, will remove table_column(app_type) and route param {type}
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

    'web_user' => 'www-data',
    'web_group' => 'www-data',
    'git_path' => 'git',

    // which origin branch for pull
    'origin_branch' => 'master',
];
