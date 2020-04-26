<?php


namespace App\Services\Station;


use App\Services\Commands\DockerCompose;

class TaskMapper
{
    public $tasks = [
            'init' => InitTask::class,
            'up' => UpTask::class,
            'down' => DownTask::class,
    ];
}