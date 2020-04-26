<?php

namespace App\Commands;

use App\Services\Commands\ConsoleCommandInterface;
use App\Services\Service;
use App\Services\Station\TaskMapper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class StationCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */ 
    protected $signature = 'station {task=init} {params?} {--queue= :mmm}';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Station';

    /**
     * Execute the console command.
     *
     * @param  Service  $service
     * @param  TaskMapper  $mapper
     *
     * @return mixed
     */
    public function handle(TaskMapper $mapper)
    {
        $arguments = $this->arguments();
        $options = $this->options();
        $command = $arguments['task'];

        if (isset($mapper->tasks[$command])) {
            /** @var ConsoleCommandInterface $commandClass */
            $commandClass = new $mapper->tasks[$command]($this);
            $commandClass->execute();
        } else {
            $this->warn('Invalid command!');
        }
        // dump($arguments,$options);

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
