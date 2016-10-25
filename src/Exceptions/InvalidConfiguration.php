<?php

/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 4:28 PM
 */
namespace LaiVu\ActivityLog\Exceptions;

use Exception;
use LaiVu\ActivityLog\Handlers\ActivityLogHandlerInterface;
use LaiVu\ActivityLog\Models\Activity;

class InvalidConfiguration extends Exception
{
    public static function modelIsNotValid($className)
    {
        return new static("The given model class `$className` does not extend `".Activity::class.'`');
    }

    public static function handlerNotFound($handlerName){
        return new static("The given handler `$handlerName` does not exist");
    }

    public static function handlerTypeNotValid($className){
        return new static("The given handler class `$className` does not extend `".ActivityLogHandlerInterface::class.'`');
    }
}