<?php

namespace App\Commands;

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

        /** @var TaskInterface $task */
        $taskClass = $mapper->tasks[$arguments['task']] ?? null;
        if ($taskClass) {
            $task = new $taskClass();
            $taskService = new BornService();
            $taskService->setTask($task);
            $taskService->generate();
        } else {
            $this->error("Invalid Command");
        }


        $tags = file_get_contents("https://api.github.com/repos/lifeeka/born/tags");
        dd($tags);

//        return Storage::download("https://github.com/lifeeka/born/blob/master/born");
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
