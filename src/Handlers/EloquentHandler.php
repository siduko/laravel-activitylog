<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 9:18 AM
 */

namespace LaiVu\ActivityLog\Handlers;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaiVu\ActivityLog\Exceptions\InvalidConfiguration;
use LaiVu\ActivityLog\Models\Activity;

class EloquentHandler implements ActivityLogHandlerInterface
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
        $activityModelClassName = $this->determineActivityModel();
        $activity = new $activityModelClassName();

        if ($performOn) {
            $activity->subject()->associate($performOn);
        }
        if ($causerBy) {
            $activity->causer()->associate($causerBy);
        }

        $activity->properties = $properties;

        $activity->description = $description;

        $activity->log_name = $logName;

        $activity->save();
    }

    /**
     * @param $maxAgeInDays
     * @return bool
     * @throws \Exception
     */
    function cleanLog($maxAgeInDays)
    {
        $cutOffDate = Carbon::now()->subDays($maxAgeInDays)->format('Y-m-d H:i:s');
        Activity::where('created_at', '<=', $cutOffDate)->delete();
        return true;
    }

    /**
     * @return Model
     * @throws InvalidConfiguration
     */
    public function determineActivityModel()
    {
        $activityModel = config('activitylog.activity_model', Activity::class);
        if (!is_a($activityModel, Activity::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }
        return $activityModel;
    }
}