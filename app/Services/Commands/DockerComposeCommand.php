<?php


namespace App\Services\Commands;


class DockerComposeCommand extends BaseCommand implements ConsoleCommandInterface
{
    public function execute()
    {
        $arguments = $this->command->arguments();
        $options = $this->command->options();

        if (!empty($options['api'])) {
            $this->dockerApplication = $options['api'];
        }

        $this->runCommand($arguments['params']);
    }
}