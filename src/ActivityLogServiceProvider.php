<?php

namespace LaiVu\ActivityLog;

use Illuminate\Support\ServiceProvider;
use LaiVu\ActivityLog\Commands\CleanActivityLogCommand;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/activitylog.php' => config_path('activitylog.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/activitylog.php', 'activitylog');

        if (! class_exists('CreateActivityLogTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_activity_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_activity_log_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'activitylog',
            ActivityLogger::class
        );

        $this->app->bind('command.activitylog:clean', CleanActivitylogCommand::class);
        $this->commands([
            'command.activitylog:clean',
        ]);
    }
}