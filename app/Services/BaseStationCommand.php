<?php


namespace App\Services;


use Illuminate\Support\Facades\File;

trait BaseStationCommand
{
    /**
     * @var string
     */
    protected $workingDirectory;

    /**
     * @param $callback
     */
    public function map($callback)
    {
        $directories = $this->getDirectories();
        foreach ($directories as $directory) {
            $configFolderPath = $directory . '/.born';
            $configPath = $configFolderPath . '/config.yml';
            $configFileExist = File::exists($configPath);
            if ($configFileExist) {
                $callback($configFolderPath);
            }
        }
    }

    /**
     * @param $command
     * @param  bool  $output
     */
    public function mapCommand($command, $output = true)
    {
        $this->map(function ($configFolderPath) use ($command, $output) {
            $this->setConfigFolderPath($configFolderPath);
            $this->executeCommand($command, $output);
        });
    }

    public function getDirectories()
    {
        return File::directories($this->workingDirectory);
    }
}
