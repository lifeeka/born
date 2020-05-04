<?php


namespace App\Services\Self;


use Illuminate\Support\Facades\File;
use Larapack\ConfigWriter\Facade as Config;
use Phar;
use Symfony\Component\Process\Process;

class BuildTask extends BaseTask implements TaskInterface
{
    private $newVersion;
    private $composer;
    /**
     * @var false|float
     */
    private $fileSize;

    public function generate($name = "born.phar")
    {
        $this->commit();

        dd();
        
        $this->setVariables();

        $this->command->line("<fg=blue>Building the archive...</>");

        if (file_exists($name)) {
            unlink($name);
        }

        $phar = new Phar($name);
        $phar->startBuffering();
        $defaultStub = $phar->createDefaultStub('application');
        $phar->buildFromDirectory('../born-php', '/^((?!\.born|.idea|.git|tests).)*$/');
        $phar->setStub($defaultStub);
        $phar->stopBuffering();
        $phar->compressFiles(Phar::GZ);
        chmod($name, 0770);

        $this->fileSize = round(File::size($name) / 1048576, 2) . 'Mb';
        $this->commit();

        $this->command->info("\nArchive has been build: " . $this->fileSize);
        $this->resetVariables();
    }

    /**
     * @throws \Exception
     */
    private function setVariables()
    {
        $currentVersion = config('app.version');
        $this->command->info("Current version:$currentVersion");
        $defaultVersion = $this->generateVersionNumber();

        $this->newVersion = $this->command->ask("What is the version", $defaultVersion);
        if ($this->newVersion == $currentVersion) {
            $this->command->error("Can't be the old version!");
            die();
        }

        $this->command->line("<fg=blue>Setting config values...</>");
        Config::write('app', ['version' => $this->newVersion, 'env' => 'production']);

        $this->command->info("\tCreating git tag");
        $process = Process::fromShellCommandline("git tag {$this->newVersion}");
        $process->run();
        $this->command->line("\t<fg=green>Tag has been created!</>");

        //remove dev packages
        $this->command->info("\tRemoving dev packages...");
        $process = Process::fromShellCommandline("composer install --no-dev");
        $process->setTimeout(60000000);
        $process->run();
        $this->command->line("\t<fg=green>Dev packages has been removed!</>");
    }

    private function resetVariables()
    {
        $this->command->line("<fg=blue>Resetting config values...</>");
        Config::write('app', ['version' => $this->newVersion, 'env' => 'dev']);

        //Install dev packages
        $this->command->info("\tReinstalling dev packages...");
        $process = Process::fromShellCommandline("composer install --dev");
        $process->setTimeout(60000000);
        $process->run();
        $this->command->line("\t<fg=green>Dev packages has been install!</>");
    }

    private function generateVersionNumber($code = 'beta')
    {
        $currentVersion = config('app.version');
        $data = explode(".", $currentVersion);

        $lastNumber = ((int)$data[2]) + 1;

        return $data[0] . '.' . $data[1] . '.' . $lastNumber . '-' . $code;
    }

    private function commit()
    {
        $message = $this->newVersion . " has been build. size: " . $this->fileSize;
        $process = Process::fromShellCommandline('git add . && git commit -m "' . $message . '"');
        $process->run();
        $this->command->info("Git commit has been made!");
    }
}