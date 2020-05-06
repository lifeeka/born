<?php


namespace App\Services;


use App\Services\Containers\ContainerInterface;
use App\Services\DockerFile\DockerImageInterface;
use Illuminate\Support\Facades\File;

class Service
{
    private $projectFolder;
    private $bornFolder = ".born";
    private $configFilename = "config.yml";
    private $dockerComposerFileName = "docker-compose.yml";
    private $projectExist;
    private $serviceName;
    private $containers = [];
    private $configFolderPath;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var Config
     */
    private $config;

    public function __construct(Environment $environment, Config $config)
    {
        $this->projectFolder = getcwd();
        $this->configFolderPath = $this->projectFolder . '/' . $this->bornFolder;
        $this->projectExist = File::exists($this->configFolderPath . '/' . $this->configFilename);
        $this->environment = $environment;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getConfigFilename(): string
    {
        return $this->configFilename;
    }

    /**
     * @return bool
     */
    public function isProjectExist(): bool
    {
        return $this->projectExist;
    }

    /**
     * @return false|string
     */
    public function getProjectFolder()
    {
        return $this->projectFolder;
    }

    /**
     * @param  false|string  $projectFolder
     *
     * @return Service
     */
    public function setProjectFolder($projectFolder)
    {
        $this->projectFolder = $projectFolder;

        return $this;
    }

    /**
     * @param  mixed  $serviceName
     *
     * @return Service
     */
    public function setServiceName(string $serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function addContainer(ContainerInterface $dockerImage)
    {
        $this->containers[] = $dockerImage;
    }

    private function createConfigFolder()
    {
        File::makeDirectory($this->projectFolder . '/' . $this->bornFolder, 0755, true, true);
    }

    /**
     * @return string
     */
    public function getConfigFolderPath(): string
    {
        return $this->configFolderPath;
    }

    public function generate()
    {
        $this->createConfigFolder();
        $this->config->setConfigFolderPath($this->configFolderPath);
        $this->config->setConfigFilename($this->configFilename);
        $this->config->setProjectName($this->serviceName);
        $this->config->generate();

        /** @var DockerImageInterface $container */
        foreach ($this->containers as $container) {
            $container->setConfigFolderPath($this->projectFolder . '/' . $this->bornFolder);
            $container->generate();
            $container->save();
        }
    }

    /**
     * @return string
     */
    public function getDockerComposerFileName(): string
    {
        return $this->dockerComposerFileName;
    }

    /**
     * @param  string  $dockerComposerFileName
     *
     * @return Service
     */
    public function setDockerComposerFileName(string $dockerComposerFileName): Service
    {
        $this->dockerComposerFileName = $dockerComposerFileName;

        return $this;
    }
    
    
}