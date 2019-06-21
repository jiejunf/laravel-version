<?php
/**
 * Copyright (c) 2019. Wistkey Limited
 */

/**
 * Created by PhpStorm.
 * User: jiejun
 * Date: 2019/4/15
 * Time: 下午6:20
 */

namespace Jiejunf\VersionService\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string platform > config:version.platform
 * @property string app_type > config:version.app_type
 * @property string app_version > store version:1.0.2
 * @property string app_version_code > build version:2
 * @property string is_force_update > y[yes],n[no]
 * @property string download_path > store page url
 * @property string description > store page update description
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string deleted_at
 */
class AppVersionLog extends Model
{
    use SoftDeletes;
}