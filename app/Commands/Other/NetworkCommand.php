<?php

namespace App\Commands\Other;

use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class NetworkCommand extends Command
{
    use BaseCommand;
    protected $signature = 'net {task?}';
    protected $description = 'Manage networks';

    /**
     * @throws BornCommandMissingException
     */
    public function handle()
    {
        $this->setNetworkList();

        $arguments = $this->arguments();
        switch ($arguments['task']) {
            case 'connect':
                $this->setServiceList();
                $services[0] = 'All';
                $services = array_values(array_merge($services, $this->services));
                $selectedServices = $this->choice('Select the service(s)', $services,
                        0, null, true);

                $selectedNetwork = $this->choice('What is the network',
                        array_column($this->networks, 1), 0);

                if (array_search('All', (array)$selectedServices) !== false) {
                    $selectedServices = $this->services;
                }

                $this->dockerApplication = 'docker';
                /** @var array $selectedServices */
                foreach ($selectedServices as $selectedService) {
                    $selectedServiceId = array_search($selectedService, $this->services);
                    $output = $this->executeCommand("network connect $selectedNetwork $selectedServiceId");
                    $this->line($output);
                }

                break;
            case 'create':

                $name = $this->ask('Network Name');
                $this->dockerApplication = 'docker';

                $output = $this->executeCommand("network create $name", false);
                $this->info("Network ID:" . $output);
                break;
            default:
                throw new BornCommandMissingException("invalid network command!", $this);
        }
    }
}
