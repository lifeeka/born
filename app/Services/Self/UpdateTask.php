<?php


namespace App\Services\Self;


class UpdateTask extends BaseTask implements TaskInterface
{
    public function generate()
    {
        $this->command->info("Checking for new versions...");
        $tags = $this->request("https://api.github.com/repos/lifeeka/born/tags");
        if ($tags) {
            $newVersion = $tags[0]['name'];
            $currentVersion = config('app.version');

            if ($newVersion != $currentVersion) {
                $this->command->info("New version available: $newVersion");
                $this->command->info("Current version: $currentVersion");
                $this->command->line("<fg=blue>Updating...</>");

                $bar = $this->outputStyle->createProgressBar(100);
                $bar->setProgressCharacter("\xF0\x9F\x9A\x83");

                $this->update(function ($data) use ($bar) {
                    $bar->setFormat('<fg=blue>%current%%</> [<fg=blue>%bar%</>] <fg=cyan>' . $data['received'] . '/' . $data['total'] . '</>');
                    $bar->advance($data['present'] - $bar->getProgress());
                });
                $bar->finish();

                $this->command->line("\n<info>Successfully Updated to $currentVersion!</info>");
            } else {
                $this->command->line("<fg=green>You are up to date 🙂</>");
            }
        }
    }

    /**
     * @param  string  $callback
     */
    private function update($callback = "")
    {
        $this->download("https://raw.githubusercontent.com/lifeeka/born/master/born.phar",
                \Phar::running(false),
                function ($val) use ($callback) {
                    if (is_callable($callback)) {
                        $callback($val);
                    }
                });
    }
}