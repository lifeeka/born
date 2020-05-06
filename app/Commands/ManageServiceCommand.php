<?php

namespace App\Commands;

use App\Services\Commands\BaseCommand;
use App\Services\Commands\CommandMapper;
use App\Services\Commands\ConsoleCommandInterface;
use App\Services\Service;
use Docker\API\Client;
use Docker\API\Model\ContainersCreatePostBody;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ManageServiceCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'manage {task=status} {params=ps} {sub_params?} {--api?}';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create services';

    /**
     * Execute the console command.
     *
     * @param  Service  $service
     * @param  CommandMapper  $mapper
     *
     * @return mixed
     */
    public function handle(Service $service, CommandMapper $mapper)
    {
        $arguments = $this->arguments();
        $options = $this->options();
        $command = $arguments['task'];
        
        if (isset($mapper->commands[$command])) {
            /** @var ConsoleCommandInterface|BaseCommand $commandClass */
            $commandClass = new $mapper->commands[$command]($this);
            $commandClass->setConfigFolderPath($service->getConfigFolderPath());
            $commandClass->setDockerComposerFileName($service->getDockerComposerFileName());
            $commandClass->execute();
        } else {
            $this->warn('Invalid command!');
        }
        // dump($arguments,$options);


    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
    }
}
