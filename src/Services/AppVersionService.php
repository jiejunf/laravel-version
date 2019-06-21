<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: yukchow
 * Date: 18/5/2018
 * Time: 2:10 PM
 */

namespace Jiejunf\VersionService\Services;

use Exception;
use Jiejunf\VersionService\Models\AppVersionLog;

class AppVersionService
{

    /**
     * Get the latest version of a type (Android or iOS)
     * @param $type
     * @param $platform
     * @return mixed
     */
    public function getLatestVersion($type, $platform)
    {
        return AppVersionLog::query()
            ->when(config('version.app_type'), function ($query) use ($type) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->where('app_type', $type);
            })
            ->where('platform', $platform)
            ->latest('app_version_code')
            ->first();
    }

    /**
     * Get past version list of a type (Android or iOS)
     * @param $type
     * @param $platform
     * @param int $page
     * @param int $per_page
     * @return mixed
     */
    public function getVersionList($type, $platform, $page = 1, $per_page = 10)
    {
        $offset = ($page - 1) * $per_page;
        return AppVersionLog::query()
            ->when(config('version.app_type'), function ($query) use ($type) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->where('app_type', $type);
            })
            ->where('platform', $platform)
            ->offset($offset)
            ->limit($per_page)
            ->latest()
            ->get();
    }

    /**
     * Create a new app version
     * @param array $version_info
     * @return bool
     * @throws Exception
     */
    public function updateOrCreateVersion($version_info)
    {
        $appType = substr($version_info['app_type'], 0, 1);
        $platform = substr($version_info['platform'], 0, 1);

        if (!$version_info['version_id']) {
            $appVersion = new AppVersionLog();
        } else {
            /** @var AppVersionLog $appVersion */
            $appVersion = AppVersionLog::query()->where('id', $version_info['version_id'])->firstOrFail();
        }
        $appVersion->platform = $platform;
        if (config('version.app_type')) $appVersion->app_type = $appType;
        $appVersion->app_version = $version_info['app_version'];
        $appVersion->app_version_code = $version_info['version_code'];
        $appVersion->is_force_update = $version_info['is_force_update'] ?? 'y';
        $appVersion->download_path = $version_info['download_path'] ?? '';
        $appVersion->description = $version_info['description'] ?? '';
        if ($appVersion->save() === false) {
            throw new Exception('', 10050);
        }
        return true;
    }
}
