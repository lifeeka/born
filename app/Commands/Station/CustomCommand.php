<?php


namespace App\Commands\Station;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class CustomCommand extends Command
{
    use BaseCommand;

    protected $signature = 'station {cmd?}';
    protected $description = 'Custom station command';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();
        if (empty($arguments['cmd'])) {
            throw new BornCommandMissingException("Command is missing. <fg=blue>{$this->signature}", $this);
        }

        $this->mapCommand($arguments['cmd']);
    }
}
