<?php


namespace App\Services\Commands;


use App\Services\DockerFile\PHPDockerFile;

class CommandMapper
{
    public $commands = [
            'cm' => DockerComposeCommand::class,
            'net' => NetworkCommand::class,
            'login' => ContainerLoginCommand::class,
    ];
}