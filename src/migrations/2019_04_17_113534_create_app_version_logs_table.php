<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAppVersionLogsTable extends Migration
{

    public function up()
    {
        Schema::create('app_version_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('platform', 45)->default('')->comment('APP系统平台-' . $this->formatConfigShortName(config('version.platform')));
            if (config('version.app_type') != null)
                $table->char('app_type', 45)->default('')->comment('APP类型-' . $this->formatConfigShortName(config('version.app_type')));
            $table->char('app_version', 20)->default('')->comment('APP版本编号-1.0.2');
            $table->char('app_version_code', 6)->default(0)->comment('APP版本号-2');
            $table->char('is_force_update', 1)->default('n')->comment('该版本是否强制更新-y[yes],n[no]');
            $table->char('download_path', 120)->default('')->comment('下载URL');
            $table->text('description')->nullable()->comment('版本描述更新内容');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function formatConfigShortName($configArray)
    {
        return join(",", $configArray);
    }

    public function down()
    {
        Schema::dropIfExists('app_version_logs');
    }
}
