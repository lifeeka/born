<?php


namespace App\Services;


use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private $domains = [];
    private $projectName = ""; 
    private $configFolderPath;
    private $configFilename;

    /**
     * @param  array  $domain
     *
     * @return Config
     */
    public function setDomain($domain): Config
    {
        $this->domains[] = $domain;

        return $this;
    }

    /**
     * @param  string  $projectName
     *
     * @return Config
     */
    public function setProjectName(string $projectName): Config
    {
        $this->projectName = $projectName;
        $this->projectName = uniqid($projectName . '-');

        return $this;
    }

    /**
     * @param  mixed  $configFolderPath
     *
     * @return Config
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;

        return $this;
    }

    /**
     * @param  mixed  $configFilename
     *
     * @return Config
     */
    public function setConfigFilename($configFilename)
    {
        $this->configFilename = $configFilename;

        return $this;
    }
 
    public function load($path)
    {
        $data = Yaml::parseFile($path); 
        $this->domains = $data['domains'] ?? []; 
        $this->projectName = $data['project-name'] ?? '';
    }

    public function generate()
    {
        $data['domains'] = $this->domains;
        $data['project-name'] = $this->projectName;

        File::put($this->configFolderPath . '/' . $this->configFilename, Yaml::dump($data));
    }

    /**
     * @return array
     */
    public function getDomains(): array
    {
        return $this->domains;
    }

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }
 
}