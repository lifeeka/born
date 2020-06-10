<?php


namespace App\Commands\Station;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class UpCommand extends Command
{
    use BaseCommand;

    protected $signature = 'su {--d|background} {--f|force}';
    protected $description = 'Custom station command';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $options = $this->options();

        $optionsCommand = $options['background'] ? "-d" : "";
        $optionsCommand .= $options['force'] ? " --force-recreate" : "";

        $this->mapCommand("up $optionsCommand", $options['details'] ?? false);
    }
}
