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
            $task = new $taskClass($this);
            $taskService = new BornService();
            $taskService->setTask($task);

            $bar = $this->output->createProgressBar(100);
            $bar->setProgressCharacter("\xF0\x9F\x9A\x83");

            $taskService->generate(function ($data) use ($bar) {
                $bar->setFormat('<fg=blue>%current%%</> [<fg=blue>%bar%</>] <fg=cyan>' . $data['received'].'/'.$data['total'] . '</>');
                $bar->advance($data['present'] - $bar->getProgress());
            });
            $bar->finish();  
            
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
