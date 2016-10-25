<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 10:14 AM
 */

namespace LaiVu\ActivityLog\Traits;


use LaiVu\ActivityLog\Models\Activity;

class CausesActivity
{
    public function activity()
    {
        return $this->morphMany(Activity::class, 'causer');
    }
    /** @deprecated Use activity() instead */
    public function loggedActivity()
    {
        return $this->activity();
    }
}