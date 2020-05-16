<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class BuildCommand extends Command
{
    use BaseCommand;
    protected $signature = 'build  {--d|background} {service?}';
    protected $description = 'Up a service';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        $options = $options['background'] ? "-d" : "";

        $this->executeCommand("up " . $arguments['service'] . " " . $options);
    }
}
