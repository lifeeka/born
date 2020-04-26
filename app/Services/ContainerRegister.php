<?php


namespace App\Services;

 

use App\Services\Containers\PHPContainer;

class ContainerRegister
{
    public $container = [
            'php' => PHPContainer::class,
    ];
    public $names = [
            "1" => "php",
    ];

    /**
     * @return array
     */
    public function getContainer(): array
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }
 
}