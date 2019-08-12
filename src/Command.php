<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace SleepCat\OpenCC;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use RuntimeException;
use InvalidArgumentException;
use Throwable;

class Command
{
    protected $binaryFile;
    protected $configPath;
    protected $inputFile;
    protected $outputFile;
    protected $configFile;

    public function __construct($binaryFile = null,$configPath = null)
    {
        $binaryFile = self::findBinary($binaryFile);

        $process = new Process([$binaryFile, '--version']);

        try {
            $code = $process->run();
        } catch (Throwable $e) {
            throw new RuntimeException('Could not check OpenCC version', 1, $e);
        }

        if (0 !== $code || !$process->isSuccessful()) {
            throw new InvalidArgumentException(sprintf(
                "OpenCC does not seem to work well, the test command resulted in an error.\n".
                "Execution returned message: \"{$process->getExitCodeText()}\"\n".
                "To solve this issue, please run this command and check your error messages to see if OpenCC was correctly installed:\n%s",
                $binaryFile.' -version'
            ));
        }

        $this->binaryFile = $this->cleanPath($binaryFile);

        if($configPath){
            if(!is_dir($configPath)){
                throw new InvalidArgumentException(sprintf(
                    "OpenCC config folder doesn't exist:\n%s",
                    $configPath
                ));
            }
            $this->configPath = $this->cleanPath($configPath);
        }
    }
    
    public static function create($binaryFile = null)
    {
        return new self($binaryFile);
    }

    public static function findBinary($binaryFile)
    {
        $binaryFile = self::cleanPath($binaryFile);

        if (!$binaryFile) {
            $binaryFile = (new ExecutableFinder())->find('opencc');
        }

        if ($binaryFile && !is_file($binaryFile)) {
            throw new OpenCCBinaryNotFoundException($binaryFile);
        }

        if (!is_executable($binaryFile)) {
            throw new InvalidArgumentException(sprintf(
                'The specified script (%s) is not executable.',
                $binaryFile
            ));
        }

        return $binaryFile;
    }

    private static function cleanPath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = rtrim($path, '/');
        return $path;
    }

    public function input($inputFile)
    {
        if(!is_file($inputFile)){
            throw new InvalidArgumentException(sprintf(
                'The input file (%s) is not exist.',
                $inputFile
            ));
        }
//        $this->inputFile = realpath($inputFile);
        $this->inputFile = $this->cleanPath($inputFile);
        return $this;
    }
    public function output($outputFile)
    {
//        if(!is_file($outputFile)){
//            touch($outputFile);
//        }
//        $this->outputFile = realpath($outputFile);
        $this->outputFile = $this->cleanPath($outputFile);
        return $this;
    }
    public function config($configFile)
    {
        $this->configFile = $this->cleanPath($configFile);
        return $this;
    }

    public function getCommand()
    {
        $commands = [
            $this->binaryFile,
            '--input',
            '"'.$this->inputFile.'"',
            '--output',
            '"'.$this->outputFile.'"',
        ];
        if($this->configFile){
            $commands[] = '--config';
            $commands[] = '"'.$this->getRealConfigFile().'"';
        }
        return implode(' ', $commands);
    }

    protected function getRealConfigFile()
    {
        $filePath = $this->cleanPath($this->configFile);
        if(!$this->configPath){
            return $filePath;
        }
        if(strpos('/','$'.$filePath)){
            return $filePath;
        }
        return $this->configPath.'/'.$this->configFile;
    }

    public function run()
    {
        // $process = Process::fromShellCommandline($this->getCommand());
        $commands = $this->getCommand();
        $process = new Process($commands);

        $output = '';
        $error = '';

        $code = $process->run(function ($type, $buffer) use (&$output, &$error) {
            if (Process::ERR === $type) {
                $error .= $buffer;
            } else {
                $output .= $buffer;
            }
        });

        $this->inputFile = $this->outputFile = $this->configFile = null;

        return new CommandResponse($process, $code, $output, $error);
    }
}