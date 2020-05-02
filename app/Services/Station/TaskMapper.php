<?php


namespace App\Services\Station;


use App\Services\Commands\DockerComposeCommand;

class TaskMapper
{
    public $tasks = [
            'cm' => AnyTask::class,
            'init' => InitTask::class,
            'up' => UpTask::class,
            'down' => DownTask::class,
    ];
}