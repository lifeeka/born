<?php

namespace App\Commands;

use App\Services\Self\BaseTask;
use App\Services\Self\BornService;
use App\Services\Self\TaskInterface;
use App\Services\Self\TaskMapper;
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
     * @param  TaskMapper  $mapper
     *
     * @return mixed
     */
    public function handle(TaskMapper $mapper)
    {
        $arguments = $this->arguments();
        $options = $this->options();

        /** @var TaskInterface|BaseTask $task */
        $taskClass = $mapper->tasks[$arguments['task']] ?? null;
        if ($taskClass) {
            $task = new $taskClass($this);
            $task->setOutputStyle($this->output);
            $taskService = new BornService();
            $taskService->setTask($task);
            
            $taskService->generate();
        } else {
            $this->error("Invalid Command");
        }
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
