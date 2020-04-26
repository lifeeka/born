<?php


namespace App\Services\Commands;


use App\Services\DockerFile\PHPDockerFile;

class CommandMapper
{
    public $commands = [
            'any' => DockerCompose::class,
    ];
}