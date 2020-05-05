<?php


namespace App\Services\Self;

use Illuminate\Console\OutputStyle;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

abstract class BaseTask
{
    /**
     * @var Command
     */
    protected $command;
    /**
     * @var OutputStyle
     */
    protected $outputStyle;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @param $url
     * @param  string  $method
     *
     * @return mixed
     */
    public function request($url, $method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Born');

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public function download($url, $destination, $callback = "")
    {
        $process = Process::fromShellCommandline("curl -o $destination $url");
        $process->setTimeout(60000000);

        $process->run(function ($type, $buffer) use ($callback) {
            $bufferArray = preg_split("/\s+/", $buffer);

            if (!is_numeric($bufferArray[1])) {
                return;
            }
            
            print_r($bufferArray);

            $returnData['present'] = $bufferArray[1] ?? 'n/a';
            $returnData['total'] = $bufferArray[2] ?? 'n/a';
            $returnData['received'] = $bufferArray[4] ?? 'n/a';
            $returnData['speed'] = $bufferArray[7] ?? 'n/a';

            if (is_callable($callback)) {
                $callback($returnData);
            }
        });
    }

    public function setOutputStyle(OutputStyle $outputStyle)
    {
        $this->outputStyle = $outputStyle;
    }
}