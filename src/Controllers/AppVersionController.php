<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: yukchow
 * Date: 18/5/2018
 * Time: 4:57 PM
 */

namespace Jiejunf\VersionService\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jiejunf\VersionService\Services\AppVersionService;

class AppVersionController extends Controller
{

    protected $appVersionService;

    public function __construct()
    {
        $this->appVersionService = new AppVersionService();
    }

    /**
     * Get latest app version of type (Android or iOS)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestVersion(Request $request)
    {
        $type = $request->route('type');
        $platform = $request->route('platform');
        $result = $this->appVersionService->getLatestVersion($type, $platform);
        return response()->json(['result' => 'success', 'data' => $result]);
    }

    /** Get latest app version of type (Android or iOS)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVersionList(Request $request)
    {
        $type = $request->route('type');
        $platform = $request->route('platform');
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;
        $result = $this->appVersionService->getVersionList($type, $platform, $page, $per_page);
        return response()->json(['result' => 'success', 'data' => $result]);
    }

    /**
     * Create a new app version
     * @param Request|mixed $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateOrCreateVersion(Request $request)
    {
        $config_platform = config('version.platform');
        $config_appType = config('version.app_type');
        $request->validate([
                'platform' => 'required|in:' . join(',', $config_platform),
                'app_version' => 'required|string',
                'version_code' => 'required',
                'is_force_update' => 'in:y,n',
                'download_path' => 'string',
                'description' => 'string',
                'version_id' => 'present',
            ] + ($config_appType ? ['app_type' => 'required|in:' . join(',', $config_appType),
            ] : []), [
            'required' => ':attribute為必填',
            'in' => ':attribute必須以下其中之一: :values',
        ], [
            'platform' => 'APP平台（' . join('/', $config_platform) . ')',
            'app_type' => 'APP種類(' . join('/', $config_appType) . ')',
            'app_version' => 'APP版本',
            'is_force_update' => '是否強制更新',
            'download_path' => '下載地址',
            'description' => '版本訊息',
            'version_id' => '版本id'
        ]);
        $result = $this->appVersionService->updateOrCreateVersion($request->all());
        return response()->json(['result' => 'success', 'data' => $result]);
    }

}
