<?php

namespace App\Commands;

use App\Services\ContainerRegister;
use App\Services\DockerCompose;
use App\Services\Environment;
use App\Services\Service;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

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
        
        dd($options,$arguments);
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
