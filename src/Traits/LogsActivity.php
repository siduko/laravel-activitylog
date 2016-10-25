<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 10:12 AM
 */

namespace LaiVu\ActivityLog\Traits;


use Illuminate\Database\Eloquent\Model;
use LaiVu\ActivityLog\ActivityLogger;
use LaiVu\ActivityLog\Models\Activity;

trait LogsActivity
{
    use DetectsChanges;

    protected static function bootLogsActivity()
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            return static::$eventName(function (Model $model) use ($eventName) {
                if (!$model->shouldLogEvent($eventName)) {
                    return;
                }
                $description = $model->getDescriptionForEvent($eventName);
                $logName = $model->getLogNameToUse($eventName);
                if ($description == '') {
                    return;
                }
                app(ActivityLogger::class)
                    ->useLog($logName)
                    ->performedOn($model)
                    ->withProperties($model->attributeValuesToBeLogged($eventName))
                    ->log($description);
            });
        });
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function getDescriptionForEvent($eventName)
    {
        return "Model have been $eventName";
    }

    public function getLogNameToUse($eventName = '')
    {
        return config('activitylog.default_log_name');
    }

    /*
     * Get the event names that should be recorded.
     */
    protected static function eventsToBeRecorded()
    {
        if (isset(static::$recordEvents)) {
            return collect(static::$recordEvents);
        }
        $events = collect([
            'created',
            'updated',
            'deleted',
        ]);
        if (collect(class_uses(__CLASS__))->contains(\Illuminate\Database\Eloquent\SoftDeletes::class)) {
            $events->push('restored');
        }
        return $events;
    }

    public function attributesToBeIgnored()
    {
        if (!isset(static::$ignoreChangedAttributes)) {
            return [];
        }
        return static::$ignoreChangedAttributes;
    }

    protected function shouldLogEvent($eventName)
    {
        if (!in_array($eventName, ['created', 'updated'])) {
            return true;
        }
        if (array_has($this->getDirty(), 'deleted_at')) {
            if ($this->getDirty()['deleted_at'] === null) {
                return false;
            }
        }
        //do not log update event if only ignored attributes are changed
        return (bool)count(array_except($this->getDirty(), $this->attributesToBeIgnored()));
    }
}