<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 9:16 AM
 */

namespace LaiVu\ActivityLog\Handlers;


use Log;

class LogHandler implements ActivityLogHandlerInterface
{

    /**
     * @param \Illuminate\Database\Eloquent\Model $performOn
     * @param \Illuminate\Database\Eloquent\Model $causerBy
     * @param array $properties
     * @param $logName
     * @param string $description
     * @return mixed
     */
    function log($performOn, $causerBy, $properties = [], $logName, $description)
    {
        $logText = "[Activity] [$logName] ";
        $userId = isset($causerBy) ? $causerBy->id : 'not set';
        $logText .= "[$userId] - $description";
        Log::info($logText);
        return true;
    }

    /**
     * @param $maxAgeInMonth
     * @return boolean
     */
    function cleanLog($maxAgeInMonth)
    {
        // TODO: Implement cleanLog() method.
    }
}