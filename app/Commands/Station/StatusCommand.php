<?php


namespace App\Commands\Station;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class StatusCommand extends Command
{
    use BaseCommand;

    protected $signature = 'station status {--g|details}';
    protected $description = 'Custom station command';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();
        if (empty($arguments['cmd'])) {
            throw new BornCommandMissingException("Command is missing. <fg=blue>{$this->signature}", $this);
        }
        $this->mapCommand("ps", $options['details'] ?? false);
    }
}
