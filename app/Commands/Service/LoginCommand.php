<?php


namespace App\Commands\Service;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class LoginCommand extends Command
{
    use BaseCommand;
    protected $signature = 'login {service?} {application=bash}';
    protected $description = 'Login to a service';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $arguments = $this->arguments();

        if (empty($arguments['service'])) {
            throw new BornCommandMissingException("Service is missing. <fg=blue>{$this->signature}", $this);
        }

        $this->executeCommand("exec {$arguments['service']} {$arguments['application']}");
    }
}
