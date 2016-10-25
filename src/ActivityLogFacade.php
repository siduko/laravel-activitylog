<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 4:18 PM
 */

namespace LaiVu\ActivityLog;


use Illuminate\Support\Facades\Facade;

class ActivityLogFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "activitylog";
    }

}