<?php


namespace App\Services\DockerFile;


use LaravelZero\Framework\Commands\Command;

class PHPDockerFile extends BaseDockerFile implements DockerImageInterface
{
    private $version;
    protected $folderName = 'php';

    public function generateInput(Command $command)
    {
        //$this->env['PHP_VERSION'] = $this->version = $command->ask("PHP version", '7.2');
        //$this->expose = $command->ask("Expose", '9000');
    }
    
    public function generate()
    {
        $this->addLine("ARG PHP_VERSION");
        $this->addLine('FROM php:${PHP_VERSION}-fpm');
        $this->addLine('WORKDIR /app');
        $this->addLine('ARG TZ');
        $this->addRun('ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone'); 
        $this->addRun('docker-php-ext-install pdo_mysql');
        $this->addRun('EXPOSE 9000');
        
        parent::generate();  
    }
}