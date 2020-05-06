<?php


namespace App\Services\Commands;


class ContainerLoginCommand extends BaseCommand implements ConsoleCommandInterface
{
    public function execute()
    {
        $arguments = $this->command->arguments();
        $options = $this->command->options();

        $this->runCommand("exec ".$arguments['params']." bash");
        shell_exec("echo {$this->dockerCommandFormatted} | clip");

    }
}