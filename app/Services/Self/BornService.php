<?php


namespace App\Services\Self;


class BornService
{
    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @param  TaskInterface  $task
     */
    public function setTask(TaskInterface $task)
    {
        $this->task = $task;
    }

    public function generate($callback = "")
    {
        $this->task->generate(function ($val) use ($callback) {
            if (is_callable($callback)) {
                $callback($val);
            }
        });
    }
}