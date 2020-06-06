<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class LogCommand extends Command
{
    use BaseCommand;
    protected $signature = 'logs {--f|tail} {service?}';
    protected $description = 'Up a service';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        $optionsCommand = $options['tail'] ? " -f" : "";

        $this->executeCommand("logs $optionsCommand {$arguments['service']}");
    }
}
