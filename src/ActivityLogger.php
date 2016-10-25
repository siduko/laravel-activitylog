<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 4:14 PM
 */

namespace LaiVu\ActivityLog;


use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use LaiVu\ActivityLog\Exceptions\InvalidConfiguration;
use LaiVu\ActivityLog\Handlers\ActivityLogHandlerInterface;
use LaiVu\ActivityLog\Models\Activity;

class ActivityLogger
{
    use Macroable;

    /** @var \Illuminate\Auth\AuthManager */
    protected $auth;

    /** @var ActivityLogHandlerInterface[] */
    protected $logHandlers = [];

    protected $logName = '';
    /** @var bool */
    protected $logEnabled;
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $performedOn;
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $causedBy;
    /** @var \Illuminate\Support\Collection */
    protected $properties;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
        $this->properties = collect();
        $authDriver = config('activitylog.default_auth_driver', $auth->getDefaultDriver());
        $this->causedBy = $auth->guard($authDriver)->user();
        $this->logName = config('activitylog.default_log_name');
        $this->logEnabled = config('activitylog.enabled', true);

        $handlers = config('activitylog.default', ['log']);

        foreach ($handlers as $handler) {
            $handlerObject = $this->determineHandler($handler);
            $this->logHandlers[] = $handlerObject;
        }
    }

    public function performedOn(Model $model)
    {
        $this->performedOn = $model;
        return $this;
    }

    public function on(Model $model)
    {
        return $this->performedOn($model);
    }

    public function causedBy(Model $model)
    {
        $this->causedBy = $model;
        return $this;
    }

    public function by($modelOrId)
    {
        return $this->causedBy($modelOrId);
    }

    /**
     * @param array|\Illuminate\Support\Collection $properties
     *
     * @return $this
     */
    public function withProperties($properties)
    {
        $this->properties = collect($properties);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function withProperty($key, $value)
    {
        $this->properties->put($key, $value);
        return $this;
    }

    public function useLog($logName)
    {
        $this->logName = $logName;
        return $this;
    }

    public function inLog($logName)
    {
        return $this->useLog($logName);
    }

    public function log($description)
    {
        if (!$this->logEnabled) {
            return;
        }

        foreach ($this->logHandlers as $handler) {
            $description = $this->replacePlaceholders($description);
            $handler->log($this->performedOn, $this->causedBy, $this->properties, $this->logName, $description);
        }
    }

    /**
     * @param $handler
     * @return ActivityLogHandlerInterface
     * @throws InvalidConfiguration
     */
    private function determineHandler($handler)
    {
        $handlerConfig = config("activitylog.handlers.$handler");
        if (!$handlerConfig) {
            throw InvalidConfiguration::handlerNotFound($handler);
        }

        $handlerDriver = $handlerConfig['driver'];
        if (!is_a($handlerDriver, ActivityLogHandlerInterface::class, true)) {
            throw InvalidConfiguration::handlerTypeNotValid($handlerDriver);
        }

        return new $handlerDriver();
    }

    /**
     * Clean out old entries in the log.
     *
     * @return bool
     */
    public function cleanLog()
    {
        $maxAgeInDays = config('activitylog.delete_records_older_than_days');

        if(is_null($maxAgeInDays) || $maxAgeInDays ==''){
            return false;
        }

        foreach ($this->logHandlers as $logHandler) {
            $logHandler->cleanLog($maxAgeInDays);
        }
        return true;
    }

    protected function replacePlaceholders($description)
    {
        return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) {
            $match = $match[0];
            $attribute =$this->getBetween($match,':', '.');
            if (! in_array($attribute, ['performedOn', 'causedBy', 'properties'])) {
                return $match;
            }
            $propertyName = substr($match, strpos($match, '.') + 1);
            return isset($this->{$attribute})&&isset($this->{$attribute}->{$propertyName})?$this->{$attribute}->{$propertyName}:$match;
        }, $description);
    }

    function getBetween($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }
}