<?php


namespace App\Services\Commands;


use App\Services\Config;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

abstract class BaseCommand
{
    protected $configFolderPath;
    protected $dockerApplication = 'docker-compose';
    protected $dockerComposerFileName = "docker-compose.yml";
    protected $configFile = 'config.yml';
    protected $networks;
    /**
     * @var Command
     */
    protected $command;
    /**
     * @var array
     */
    protected $services;
    /**
     * @var string|string[]
     */
    protected $dockerCommandFormatted;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @param  mixed  $configFolderPath
     *
     * @return BaseCommand
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;

        return $this;
    }

    /**
     * @param  string  $dockerComposerFileName
     *
     * @return BaseCommand
     */
    public function setDockerComposerFileName(string $dockerComposerFileName): BaseCommand
    {
        $this->dockerComposerFileName = $dockerComposerFileName;

        return $this;
    }

    public function runCommand($command, $output = true)
    {
        $config = new Config();
        $config->load($this->configFolderPath . '/' . $this->configFile);

        if ($this->dockerApplication == 'docker-compose') {
            $dockerCommand = "$this->dockerApplication 
            -p {$config->getProjectName()}
            -f \"{$this->configFolderPath}/{$this->dockerComposerFileName}\"
            --env-file=\"{$this->configFolderPath}/.env\"
            $command";
        } else {
            $dockerCommand = "$this->dockerApplication $command";
        }

        $this->dockerCommandFormatted = trim(preg_replace('/\s\s+/', ' ', $dockerCommand));;

        if ($output) {
            $this->command->info("NAME: " . $config->getProjectName());
            $this->command->info("COMMAND: " . str_replace("\r", " \\", $dockerCommand));
        }

        $process = Process::fromShellCommandline($dockerCommand);
        $process->setTimeout(60000000);
        try {
            $process->setTty(true);
        } catch (\RuntimeException $exception) {
        }

        if ($output) {
            $process->run(function ($type, $buffer) {
                $this->command->line("<fg=cyan;>$buffer</>");
            });
        } else {
            $process->run();
        }

        return $process->getOutput();
    }

    protected function setNetworkList()
    {
        $process = Process::fromShellCommandline("docker network ls --no-trunc", $this->configFolderPath);
        $process->run();
        $networks = array_map(function ($networkString) {
            if ($networkString) {
                return preg_split("/\s+/", $networkString);
            } else {
                return [];
            }
        }, explode("\n", $process->getOutput()));

        unset($networks[0]);
        $networks = array_map('array_filter', $networks);
        $this->networks = array_filter($networks);
    }

    protected function setServiceList()
    {
        $this->dockerApplication = 'docker-compose';
        $output = $this->runCommand('ps --services', false);
        $services = array_filter(explode("\n", $output), 'strlen');
        foreach ($services as $service) {
            $serviceId = trim($this->runCommand("ps -q $service", false));
            $this->services[$serviceId] = $service;
        }
    }
}
