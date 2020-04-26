<?php


namespace App\Services;


use App\Services\Containers\ContainerInterface;
use Illuminate\Support\Facades\File;

class DockerCompose
{
    private $version = '3.7';
    private $containers = [];
    private $configFolderPath;
    private $filename = "docker-compose.yml";
    private $contents = "";

    public function addContainer(ContainerInterface $container)
    {
        $this->containers[] = $container;
    }

    /**
     * @param  mixed  $configFolderPath
     *
     * @return DockerCompose
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;

        return $this;
    }

    /**
     * @param  string  $filename
     *
     * @return DockerCompose
     */
    public function setFilename(string $filename): DockerCompose
    {
        $this->filename = $filename;

        return $this;
    }

    public function generate()
    {
        File::put($this->configFolderPath . '/' . $this->filename, $this->contents);
    }
}