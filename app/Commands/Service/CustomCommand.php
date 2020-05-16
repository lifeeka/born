<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class CustomCommand extends Command
{
    use BaseCommand;
    protected $signature = 'cm {cmd?}';
    protected $description = 'Login to a service';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();

        if (empty($arguments['cmd'])) {
            throw new BornCommandMissingException("Command is missing. <fg=blue>{$this->signature}", $this);
        }

        $this->executeCommand($arguments['cmd']);
    }
}
