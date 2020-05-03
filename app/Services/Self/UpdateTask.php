<?php


namespace App\Services\Self;


class UpdateTask extends BaseTask implements TaskInterface
{
    public function generate($callback = "")
    {
        $tags = $this->request("https://api.github.com/repos/lifeeka/born/tags");
        if ($tags) {
            $newVersion = $tags[0]['name'];
            $currentVersion = config('app.version');
             
            if ($newVersion != $currentVersion) {
                $this->command->info("New version available: $newVersion");
                $this->command->info("Current version: $currentVersion");
                $this->command->line("<fg=blue>Updating...</>");
                $this->update(function ($val) use ($callback) {
                    if (is_callable($callback)) {
                        $callback($val);
                    }
                });
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
        $this->download("https://raw.githubusercontent.com/lifeeka/born/master/born",
                'born',
                function ($val) use ($callback) {
                    if (is_callable($callback)) {
                        $callback($val);
                    }
                });
    }
}