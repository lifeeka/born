<?php


namespace App\Services\DockerFile;


use LaravelZero\Framework\Commands\Command;

interface DockerImageInterface
{
    public function addRun($code);
    public function addLine($code);
    public function addNewLine();
    public function setConfigFolderPath($configFolderPath);
    public function getEnv();
    public function generateInput(Command $command);
    public function generate();
    public function save();
}