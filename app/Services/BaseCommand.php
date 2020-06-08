<?php


namespace App\Services;


use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

trait BaseCommand
{
    use BaseStationCommand;

    protected $configFolderPath;
    protected $dockerApplication = 'docker-compose';
    protected $dockerComposerFileName = "docker-compose.yml";
    protected $configFile = 'config.yml';
    protected $networks;
    protected $projectFolder;
    protected $projectExist;
    protected $environment;
    /**
     * @var Config $config
     */
    protected $config;
    protected $bornFolder = ".born";
    protected $configFilename = "config.yml";

    /**
     * @var array
     */
    protected $services;
    /**
     * @var string|string[]
     */
    protected $dockerCommandFormatted;


    public function __construct(Environment $environment, Config $config)
    {
        $this->projectFolder = getcwd();
        $this->configFolderPath = $this->projectFolder . '/' . $this->bornFolder;
        $this->projectExist = File::exists($this->configFolderPath . '/' . $this->configFilename);
        $this->environment = $environment;
        $this->config = $config;
        $this->workingDirectory = "E:\projects\lifeeka\services";

        parent::__construct();
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

    public function executeCommand($command, $output = true)
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
            $this->info("NAME: " . $config->getProjectName());
            $this->info("COMMAND: " . str_replace("\r", " \\", $this->dockerCommandFormatted));
        }

        $process = Process::fromShellCommandline($this->dockerCommandFormatted);
        $process->setTimeout(60000000);
        if (Process::isTtySupported()) {
            $process->setTty(true);
        }

        if ($output) {
            $process->run(function ($type, $buffer) {
                $this->line("<fg=cyan;>$buffer</>");
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
        $output = $this->executeCommand('ps --services', false);
        $services = array_filter(explode("\n", $output), 'strlen');
        foreach ($services as $service) {
            $serviceId = trim($this->executeCommand("ps -q $service", false));
            $this->services[$serviceId] = $service;
        }
    }

    public function request($url, $method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Born');

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public function download($url, $destination, $callback = "")
    {
        $process = Process::fromShellCommandline("curl -o $destination $url");
        $process->setTimeout(60000000);

        $process->run(function ($type, $buffer) use ($callback) {
            $bufferArray = preg_split("/\s+/", $buffer);

            if (!is_numeric($bufferArray[1] ?? null)) {
                return;
            }
            $returnData['present'] = $bufferArray[1] ?? 'n/a';
            $returnData['total'] = $bufferArray[2] ?? 'n/a';
            $returnData['received'] = $bufferArray[4] ?? 'n/a';
            $returnData['speed'] = $bufferArray[7] ?? 'n/a';

            if (is_callable($callback)) {
                $callback($returnData);
            }
        });
    }



}
