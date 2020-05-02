<?php


namespace App\Services\Station;


class InitTask extends BaseStationTask implements StationTaskInterface
{
    public function execute()
    {
        $this->mapCommand("ps");
    }
}