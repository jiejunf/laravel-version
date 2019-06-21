<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: jiejun
 * Date: 2019/3/16
 * Time: 上午4:41
 */

namespace Jiejunf\VersionService\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ServerVersionController extends Controller
{


    public function getCurrentVersion()
    {
        $kv_result = $this->_getCurrentVersion();
        return response()->json([
            'result' => 'success',
            'message' => $kv_result->only(['date', 'comment']),
        ]);
    }

    /**
     * @return Collection
     */
    private function _getCurrentVersion()
    {
        $git = config('version.git_path', 'git');
        exec($git . ' log -1', $result);
        $c_result = collect($result);
        $c_result->shift();
        $kv_result = collect();
        $c_result->each(function ($item) use (&$kv_result) {
            $msg = explode(': ', $item);
            if (count($msg) > 1)
                $kv_result[trim($msg[0])] = trim($msg[1]);
            else
                $kv_result['comment'] = (($kv_result['comment'] ?? '') . ltrim($item, ' '));
        });
        $kv_result['date'] = Carbon::parse($kv_result['Date'])->toDateTimeString();
        return $kv_result;
    }

    /**
     * @return JsonResponse
     * @version 2.1
     */
    public function updateVersion()
    {
        ignore_user_abort(1);
        $pre_comment = $this->_getCurrentVersion();
        $app_root = base_path();
        $git = config('version.git_path', 'git');
        $result = shell_exec($git . ' checkout ' . $app_root .
            ' && ' . $git . ' pull origin master && chown -R ' . config('version.web_user') . ':' . config('version.web_group') . ' ' . $app_root .
            ' && chmod -R 755 ' . $app_root .
            ' && chmod -R ug+rwx ' . $app_root . '/storage ' . $app_root . '/bootstrap/cache' .
            ' && chmod -R 770 ' . $app_root . '/.git');
        Storage::disk('local')->append('version-hook.log', str_pad('', 20, '='));
        Storage::disk('local')->append('version-hook.log', $result);
        $current_comment = $this->_getCurrentVersion();
        if ($pre_comment->diff($current_comment)->isNotEmpty()) {
            Storage::disk('local')->append(
                'version-hook.log',
                str_pad($pre_comment['date'], mb_strlen($pre_comment['comment'])) . ' => ' . $current_comment['date'] . "\n" .
                $pre_comment['comment'] . ' => ' . $current_comment['comment']
            );
        }
        return response()->json([
            'result' => 'success',
            'message' => $current_comment,
        ]);
    }
}