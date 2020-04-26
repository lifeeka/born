<?php


namespace App\Services\Containers;


use LaravelZero\Framework\Commands\Command;

interface ContainerInterface
{
    public function generate();
    public function save(); 
    public function generateInput(Command $command);
    public function getEnv();
    public function setConfigFolderPath($configFolderPath);
}