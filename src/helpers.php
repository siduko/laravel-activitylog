<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 4:34 PM
 */
use LaiVu\ActivityLog\ActivityLogger;

if (!function_exists('activity')) {
    /**
     * @param null $logName
     * @return ActivityLogger
     */
    function activity($logName = null)
    {
        $defaultLogName = config('activitylog.default_log_name');
        return app(ActivityLogger::class)->useLog($logName ? $logName : $defaultLogName);
    }
}