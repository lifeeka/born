<?php


namespace App\Services;


use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

class Environment
{
    protected $configFolderPath;
    protected $content = "";

    public function __construct()
    {
    }

    public function add($key, $value)
    {
        $this->content .= "$key=$value\n";
    }

    public function generate()
    {
        File::put($this->configFolderPath . '/.env', $this->content);
    }

    /**
     * @param  mixed  $configFolderPath
     *
     * @return Environment
     */
    public function setConfigFolderPath($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;

        return $this;
    }
}