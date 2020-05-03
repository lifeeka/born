<?php

namespace App\Commands;

use App\Services\ContainerRegister;
use App\Services\DockerCompose;
use App\Services\Environment;
use App\Services\Service;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

use Illuminate\Support\Facades\Storage;

class BornCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'self {task?} {--s|self}';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Born';

    /**
     * Execute the console command.
     *
     * @param  Service  $service
     *
     * @param  DockerCompose  $dockerCompose
     * @param  ContainerRegister  $containerRegister
     *
     * @param  Environment  $environment
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        return Storage::download("https://github.com/lifeeka/born/blob/master/born");
        
        #dd($options,$arguments);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
