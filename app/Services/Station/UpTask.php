<?php


namespace App\Services\Station;


use App\Services\Config;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class UpTask extends BaseStationTask implements StationTaskInterface
{
    public function execute()
    {
        $this->mapCommand("up -d");
    }
}