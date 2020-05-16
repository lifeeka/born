<?php


namespace App\Commands\Station;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class ListCommand extends Command
{
    use BaseCommand;
    protected $signature = 'station {ps}';
    protected $description = 'List all services';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $this->mapCommand("ps");
    }
}
