<?php


namespace App\Services\Station;


class AnyTask extends BaseStationTask implements StationTaskInterface
{
    public function execute()
    { 
        $arguments = $this->command->arguments();

        $this->mapCommand($arguments['params']);
    }
}