<?php


namespace App\Services\Self;


use Larapack\ConfigWriter\Facade as Config;
use Symfony\Component\Process\Process;

class SyncTagsTask extends BaseTask implements TaskInterface
{
    public function generate()
    { 
        $process = Process::fromShellCommandline("git tag -d $(git tag) && git fetch --tags");
        $process->run();
        
        $process = Process::fromShellCommandline("git tag");
        $process->run(function ($type, $buffer) {
            $this->command->line("<fg=cyan;>$buffer</>");
        });
        
        $process = Process::fromShellCommandline('git describe --abbrev=0 --tags');
        $process->run();
        $tags = $this->request("https://api.github.com/repos/lifeeka/born/tags"); 
        Config::write('app', ['version' => $tags[0]['name']]);

        $this->command->info("Tags has been synced");
    }
}