<?php


namespace App\Commands\Self;


use App\Exceptions\BornCommandMissingException;
use App\Services\BaseCommand;
use Exception;
use Illuminate\Support\Facades\File;
use Larapack\ConfigWriter\Facade as Config;
use LaravelZero\Framework\Commands\Command;
use Phar;
use Symfony\Component\Process\Process;

class BuildCommand extends Command
{
    use BaseCommand;
    protected $signature = 'self-build';
    protected $description = 'Build Born';
    private $newVersion;
    /**
     * @var false|float
     */
    private $fileSize;

    /**
     * @throws BornCommandMissingException
     * @throws Exception
     */
    public function handle()
    {
        $this->generate();
    }

    /**
     * @param  string  $name
     *
     * @throws Exception
     */
    private function generate($name = "born.phar")
    {
        $this->setVariables();

        $this->line("<fg=blue>Building the archive...</>");

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
        $this->info("\nArchive has been build: " . $this->fileSize);
        $this->commit();

        $this->resetVariables();
    }

    /**
     * @throws Exception
     */
    private function setVariables()
    {
        $currentVersion = config('app.version');
        $this->info("Current version:$currentVersion");
        $defaultVersion = $this->generateVersionNumber();

        $this->newVersion = $this->ask("What is the version", $defaultVersion);
        if ($this->newVersion == $currentVersion) {
            $this->error("Can't be the old version!");
            die();
        }

        $this->line("<fg=blue>Setting config values...</>");
        Config::write('app', ['version' => $this->newVersion, 'env' => 'production']);

        $this->info("\tCreating git tag");
        $process = Process::fromShellCommandline("git tag {$this->newVersion}");
        $process->run();
        $this->line("\t<fg=green>Tag has been created!</>");

        //remove dev packages
        $this->info("\tRemoving dev packages...");
        $process = Process::fromShellCommandline("composer install --no-dev");
        $process->setTimeout(60000000);
        $process->run();
        $this->line("\t<fg=green>Dev packages has been removed!</>");
    }

    private function resetVariables()
    {
        $this->line("<fg=blue>Resetting config values...</>");
        Config::write('app', ['version' => $this->newVersion, 'env' => 'dev']);

        //Install dev packages
        $this->info("\tReinstalling dev packages...");
        $process = Process::fromShellCommandline("composer install --dev");
        $process->setTimeout(60000000);
        $process->run();
        $this->line("\t<fg=green>Dev packages has been install!</>");
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
        $this->info("Git commit has been made!");
    }
}
