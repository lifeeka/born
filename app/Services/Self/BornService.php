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

    public function generate()
    {
        $this->task->generate();
    }
}