<?php

namespace App\Commands;

use App\Services\Self\BaseTask;
use App\Services\Self\BornService;
use App\Services\Self\TaskInterface;
use App\Services\Self\TaskMapper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use NunoMaduro\LaravelConsoleSummary\SummaryCommand;

class DefaultCommand extends SummaryCommand
{
    protected const FORMAT = 'txt';

}
