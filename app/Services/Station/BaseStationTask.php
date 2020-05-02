<?php


namespace App\Services\Station;


use App\Services\Config;
use App\Services\Environment;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

abstract class BaseStationTask
{
    protected $workingDirectory;
    protected $bornFolder = ".born";
    /**
     * @Command Command
     */
    protected $command;

    public function __construct(Command $command)
    {
        $this->workingDirectory = "E:\projects\lifeeka\services";
        $this->command = $command;
    }

    public function getDirectories()
    {
        return File::directories($this->workingDirectory);
    }

    public function map($callback)
    {
        $directories = $this->getDirectories();
        foreach ($directories as $directory) {
            $configFolderPath = $directory . '/.born';
            $configPath = $configFolderPath . '/config.yml';
            $configFileExist = File::exists($configPath);
            if ($configFileExist) {
                $callback($configFolderPath, $configPath);
            }
        }
    }

    public function mapCommand($command)
    {
        $this->map(function ($configFolderPath, $configPath) use ($command) {
            $config = new Config();
            $config->load($configPath);
            
            $dockerCommand = "docker-compose -p {$config->getProjectName()} $command";
            
            $this->command->info("name: " . $config->getProjectName());
            $this->command->info("command: ". $dockerCommand);
            $this->command->info("folder: ". $configFolderPath);

            $process = Process::fromShellCommandline($dockerCommand, $configFolderPath);
            $process->setTimeout(60000000);
            $process->run(function ($type, $buffer) {
                $this->command->line("<fg=cyan;>$buffer</>");
            });
        });
    }
}