<?php


namespace App\Commands\Self;


use App\Services\BaseCommand;
use LaravelZero\Framework\Commands\Command;

class UpdateCommand extends Command
{
    use BaseCommand;
    protected $signature = 'self-update';
    protected $description = 'Update Born';

    /**
     */
    public function handle()
    {
        $this->info("Checking for new versions...");
        $tags = $this->request("https://api.github.com/repos/lifeeka/born/tags");
        if ($tags) {
            $newVersion = $tags[0]['name'];
            $currentVersion = config('app.version');

            if ($newVersion != $currentVersion) {
                $this->info("New version available: $newVersion");
                $this->info("Current version: $currentVersion");
                $this->line("<fg=blue>Updating...</>");

                $bar = $this->output->createProgressBar(100);
                $bar->setProgressCharacter("||");

                $this->download("https://raw.githubusercontent.com/lifeeka/born/master/born.phar",
                        \Phar::running(false),
                        function ($data) use ($bar) {
                            $bar->setFormat('<fg=blue>%current%%</> [<fg=blue>%bar%</>] <fg=cyan>' . $data['received'] . '/' . $data['total'] . '</>');
                            $bar->advance($data['present'] - $bar->getProgress());
                        });


                $bar->finish();

                $this->line("\n<info>Successfully Updated to $newVersion!</info>");
            } else {
                $this->line("<fg=green>You are up to date</>");
            }
        }
    }
}
