<?php


namespace App\Services\Commands;


use Symfony\Component\Process\Process;

class DockerCompose extends BaseCommand implements ConsoleCommandInterface
{
    public function execute()
    {
        $process = Process::fromShellCommandline("docker-compose ps", $this->configFolderPath);
        $process->run();
        echo $this->configFolderPath . "\n";
        echo $process->getOutput() . "\n";
        echo $process->getErrorOutput() . "\n";
    }
}