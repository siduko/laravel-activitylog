<?php
/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/24/2016
 * Time: 3:52 PM
 */

return [
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    'activity_model' => '\LaiVu\ActivityLog\Models\Activity',

    'default_log_name' => 'default',

    'default' => ['eloquent'],

    /**
     * When set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    'delete_records_older_than_days' => 365,

    'handlers' => [
        'log' => [
            'driver' => '\LaiVu\ActivityLog\Handlers\LogHandler'
        ],
        'eloquent' => [
            'driver' => '\LaiVu\ActivityLog\Handlers\EloquentHandler'
        ]
    ]
];