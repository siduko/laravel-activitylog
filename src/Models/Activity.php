<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 4:04 PM
 */
namespace LaiVu\ActivityLog\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

class Activity extends Eloquent
{
    protected $table = 'activity_log';
    public $guarded = [];
    protected $casts = [
        'properties' => 'collection',
    ];

    /**
     * @return MorphTo
     */
    public function subject()
    {
        if (config('activitylog.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function causer()
    {
        return $this->morphTo();
    }
    /**
     * Get the extra properties with the given name.
     *
     * @param string $propertyName
     *
     * @return mixed
     */
    public function getExtraProperty($propertyName)
    {
        return array_get($this->properties->toArray(), $propertyName);
    }

    /**
     * @return Collection
     */
    public function getChangesAttribute()
    {
        return collect(array_filter($this->properties->toArray(), function ($key) {
            return in_array($key, ['attributes', 'old']);
        }, ARRAY_FILTER_USE_KEY));
    }
    public function scopeInLog(Builder $query, ...$logNames)
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }
        return $query->whereIn('log_name', $logNames);
    }
    /**
     * Scope a query to only include activities by a given causer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $causer
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCausedBy(Builder $query, Model $causer)
    {
        return $query
            ->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey());
    }
    /**
     * Scope a query to only include activities for a given subject.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $subject
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSubject(Builder $query, Model $subject)
    {
        return $query
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
    }
}