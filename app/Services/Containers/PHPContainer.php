<?php


namespace App\Services\Containers;


use App\Services\DockerFile\PHPDockerFile;
use LaravelZero\Framework\Commands\Command;

class PHPContainer extends BaseContainer implements ContainerInterface
{
 
    public function __construct()
    {
        $this->container = new PHPDockerFile();
    }

}