<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class StatusCommand extends Command
{
    use BaseCommand;
    protected $signature = 'ps {service?}';
    protected $description = 'Get service status';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();

        $this->executeCommand("ps {$arguments['service']}");
    }
}
