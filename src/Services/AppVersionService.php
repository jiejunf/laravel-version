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
     * @param string $version
     * @return string
     */
    public static function versionPad($version)
    {
        return sprintf('v' .
            preg_replace('/\d+/', '%04s', $version)
            , ...explode('.', preg_replace('/\D+/', '.', $version))
        );
    }

    /**
     * Get the latest version of a type (Android or iOS)
     * @param $type
     * @param $platform
     * @param $customerVersion
     * @return AppVersionLog
     */
    public function getLatestVersion($type, $platform, $customerVersion = 0)
    {
        /** @var AppVersionLog $latestVersion */
        $latestVersion = AppVersionLog::query()
            ->when(config('version.app_type'), function ($query) use ($type) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->where('app_type', $type);
            })
            ->where('platform', $platform)
            ->latest('app_version_code')
            ->first();
        if (AppVersionLog::query()->where([
            ['app_version_code', '>', self::versionPad($customerVersion),],
            ['is_force_update', 'y']
        ])->exists()) {
            $latestVersion->is_force_update = 'y';
        }
        return $latestVersion;
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
     * @return AppVersionLog
     * @throws Exception
     */
    public function updateOrCreateVersion($version_info)
    {
        if (!$version_info['version_id']) {
            $appVersion = new AppVersionLog();
        } else {
            /** @var AppVersionLog $appVersion */
            $appVersion = AppVersionLog::query()->where('id', $version_info['version_id'])->firstOrFail();
        }
        $appVersion->platform = $version_info['platform'];
        if (config('version.app_type')) $appVersion->app_type = $version_info['app_type'];
        $appVersion->app_version = $version_info['app_version'];
        $appVersion->is_force_update = $version_info['is_force_update'] ?? 'y';
        $appVersion->download_path = $version_info['download_path'] ?? '';
        $appVersion->description = $version_info['description'] ?? '';
        if ($appVersion->save() === false) {
            throw new Exception('', 10050);
        }
        return $appVersion;
    }
}
