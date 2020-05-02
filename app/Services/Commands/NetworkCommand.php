<?php


namespace App\Services\Commands;


use LaravelZero\Framework\Commands\Command;

class NetworkCommand extends BaseCommand implements ConsoleCommandInterface
{
    protected $dockerApplication = 'docker';

    /**
     * NetworkCommand constructor.
     *
     * @param  Command  $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);
    }

    public function execute()
    {
        $this->setNetworkList();

        $arguments = $this->command->arguments();
        switch ($arguments['params']) {
            case 'connect':
                $this->setServiceList();
                $services[0] = 'All';
                $services = array_values(array_merge($services, $this->services));
                $selectedServices = $this->command->choice('Select the service(s)', $services,
                        0, null, true);

                $selectedNetwork = $this->command->choice('What is the network',
                        array_column($this->networks, 1), 0);

                if (array_search('All', (array)$selectedServices) !== false) { 
                    $selectedServices = $this->services;
                }

                $this->dockerApplication = 'docker';
                /** @var array $selectedServices */
                foreach ($selectedServices as $selectedService) {
                    $selectedServiceId = array_search($selectedService, $this->services);
                    $output = $this->runCommand("network connect $selectedNetwork $selectedServiceId");
                    $this->command->line($output);
                }

                break;
            case 'create':

                $name = $this->command->anticipate('Network Name', array_column($this->networks, 1));
                $this->dockerApplication = 'docker';
                $output = $this->runCommand("network create $name", false);
                $this->command->info("Network ID:" . $output);
                break;
        }
        // $this->runCommand($cmd);
    }
}