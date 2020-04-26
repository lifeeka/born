<?php


namespace App\Services\Containers;


use App\Services\DockerFile\BaseDockerFile;
use App\Services\DockerFile\DockerImageInterface;
use App\Services\DockerFile\PHPDockerFile;
use LaravelZero\Framework\Commands\Command;

abstract class BaseContainer
{
    /**
     * @var DockerImageInterface
     */
    protected $container;
    protected $configFolderPath;

    public function generate()
    {
        $this->container->generate();
    }

    public function save()
    {
        $this->container->save();
    }

    public function generateInput(Command $command)
    {
        $this->container->generateInput($command);
    }

    public function getEnv()
    {
        return $this->container->getEnv();
    }

    /**
     * @param $configFolderPath
     *
     * @return $this
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;
        $this->container->setConfigFolderPath($configFolderPath);

        return $this;
    }
}