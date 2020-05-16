<?php


namespace App\Exceptions;


use LaravelZero\Framework\Commands\Command;

class BornException extends \Exception
{
    private $command;

    /**
     * BornException constructor.
     *
     * @param  $message
     * @param  Command  $command
     */
    public function __construct($message, Command $command)
    {
        $this->command = $command;
        parent::__construct($message, 0, null);
    }

    /**
     * @return Command
     */
    public function getCommand(): ?Command
    {
        return $this->command;
    }
}