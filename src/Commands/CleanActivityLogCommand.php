<?php
namespace LaiVu\ActivityLog\Commands;
use Illuminate\Console\Command;

/**
 * Created by PhpStorm.
 * User: Lai Vu
 * Date: 10/25/2016
 * Time: 9:53 AM
 */
class CleanActivityLogCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'activitylog:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old records from the activity log.';
    public function handle()
    {
        $this->comment('Cleaning activity log...');
        app('activitylog')->cleanLog();
        $this->comment('All done!');
    }
}