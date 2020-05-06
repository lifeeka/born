<?php


namespace App\Services\Station;


class TaskMapper
{
    public $tasks = [
            'cm' => AnyTask::class,
            'init' => InitTask::class,
            'up' => UpTask::class,
            'down' => DownTask::class,
    ];
}