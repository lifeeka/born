<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class ReDownCommand extends Command
{
    use BaseCommand;
    protected $signature = 'redown {--d|background} {--f|force}';
    protected $description = 'Down and up service!';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $options = $this->options();

        $this->executeCommand("down");

        $optionsCommand = $options['background'] ? "-d" : "";
        $optionsCommand .= $options['force'] ? " --force-recreate" : "";
        $this->executeCommand("up $optionsCommand");
    }
}
