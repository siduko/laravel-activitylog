<?php

/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 8:46 AM
 */
namespace LaiVu\ActivityLog\Handlers;

interface ActivityLogHandlerInterface
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $performOn
     * @param \Illuminate\Database\Eloquent\Model $causerBy
     * @param array $properties
     * @param $logName
     * @param string $description
     * @return mixed
     */
    function log($performOn, $causerBy, $properties = [], $logName, $description);

    /**
     * @param $maxAgeInMonth
     * @return boolean
     */
    function cleanLog($maxAgeInMonth);

}