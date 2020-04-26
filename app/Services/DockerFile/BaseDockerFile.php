<?php


namespace App\Services\DockerFile;


use Illuminate\Support\Facades\File;

abstract class BaseDockerFile
{
    protected $timeZone;
    protected $expose = [];
    protected $content = "";
    protected $folderName;
    protected $configFolderPath;
    protected $env = [];

    public function addRun($code)
    {
        $this->addLine("RUN ".$code);
    }

    public function addLine($code)
    {
        $this->content .= $code . "\n";
    }

    public function addNewLine()
    {
        $this->content .= "\n";
    }

    /**
     * @param  mixed  $configFolderPath
     *
     * @return BaseDockerFile
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;

        return $this;
    }

    public function generate()
    {
        File::makeDirectory($this->configFolderPath . '/' . $this->folderName, 0755, true, true);
    }

    public function save($fileName = "Dockerfile")
    {
        File::put($this->configFolderPath . '/' . $this->folderName . '/' . $fileName, $this->content,true);
    }

    public function getEnv()
    {
        return $this->env;
    }
}