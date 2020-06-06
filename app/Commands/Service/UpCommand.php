<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class UpCommand extends Command
{
    use BaseCommand;
    protected $signature = 'up  {--d|background} {--f|force} {service?}';
    protected $description = 'Up a service';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        $optionsCommand = $options['background'] ? "-d" : "";
        $optionsCommand .= $options['force'] ? " --force-recreate" : "";

        $this->executeCommand("up $optionsCommand {$arguments['service']}");
    }
}
