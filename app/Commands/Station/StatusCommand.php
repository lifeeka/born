<?php


namespace App\Commands\Station;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class StatusCommand extends Command
{
    use BaseCommand;

    protected $signature = 'ss {--g|details}';
    protected $description = 'Custom station command';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $options = $this->options();
        $this->mapCommand("ps", $options['details'] ?? false);
    }
}
