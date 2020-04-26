<?php

namespace App\Commands;

use App\Services\ContainerRegister;
use App\Services\Containers\ContainerInterface;
use App\Services\DockerCompose;
use App\Services\DockerFile\DockerImageInterface;
use App\Services\Environment;
use App\Services\Service;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CreateServiceCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'service {task} {--f|force}';
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
     *
     * @param  DockerCompose  $dockerCompose
     * @param  ContainerRegister  $containerRegister
     *
     * @param  Environment  $environment
     *
     * @return mixed
     */
    public function handle(Service $service,DockerCompose $dockerCompose, ContainerRegister $containerRegister, 
            Environment 
$environment)
    {
        $arguments = $this->arguments();
        $options = $this->options(); 
        
        if ($service->isProjectExist() && !$options['force']) {
            $this->warn('Born is already been initialized');
        } else {
            $service->setServiceName($this->ask('Service name', basename(getcwd())));
            $environment->setConfigFolderPath($service->getConfigFolderPath());
            $dockerCompose->setConfigFolderPath($service->getConfigFolderPath());

            $selectedService = $this->choice('Containers to include', $containerRegister->getNames(), 1, null,
                    true);

            /** @var array $selectedService */
            foreach ($selectedService as $serviceKey) {
                /** @var ContainerInterface $container */
                $container = new  $containerRegister->container[$serviceKey];
                $container->generateInput($this); 
                $service->addContainer($container);
                $dockerCompose->addContainer($container);

                foreach ($container->getEnv() as $envKey => $envValue) {
                    $environment->add($envKey, $envValue);
                }
            }
            $service->generate();
            $dockerCompose->generate();
            $environment->generate();
        }
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
        // $schedule->command(static::class)->everyMinute();
    }
}
