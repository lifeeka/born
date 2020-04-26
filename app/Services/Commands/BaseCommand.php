<?php


namespace App\Services\Commands;


abstract class BaseCommand
{
    protected $configFolderPath;

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

}