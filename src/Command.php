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
    protected $binary;
    protected $inputFile;
    protected $outputFile;
    protected $configFile;

    public function __construct($binary = null)
    {
        $binary = self::findBinary($binary);

        $process = new Process([$binary, '--version']);

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
                $binary.' -version'
            ));
        }

        $this->binary = $binary;
    }
    
    public static function create($binary = null)
    {
        return new self($binary);
    }

    public static function findBinary($binary)
    {
        $binary = self::cleanPath($binary);

        if (!$binary) {
            $binary = (new ExecutableFinder())->find('opencc');
        }

        // Add a proper directory separator at the end if path is not empty.
        // If it's empty, then it's set in the global path.
        if ($binary && !is_file($binary)) {
            throw new OpenCCBinaryNotFoundException($binary);
        }

        if (!is_executable($binary)) {
            throw new InvalidArgumentException(sprintf(
                'The specified script (%s) is not executable.',
                $binary
            ));
        }

        return $binary;
    }

    private static function cleanPath($path)
    {
        $path = str_replace('\\', '/', $path);

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
        $this->inputFile = $inputFile;
        return $this;
    }
    public function output($outputFile)
    {
//        if(!is_file($outputFile)){
//            touch($outputFile);
//        }
//        $this->outputFile = realpath($outputFile);
        $this->outputFile = $outputFile;
        return $this;
    }
    public function config($configFile)
    {
        $this->configFile = $configFile;
        return $this;
    }

    public function getCommand(): string
    {
        $commands = [
            $this->binary,
            '--input',
            '"'.$this->inputFile.'"',
            '--output',
            '"'.$this->outputFile.'"',
        ];
        if($this->configFile){
            $commands[] = '--config';
            $commands[] = '"'.$this->configFile.'"';
        }
        return implode(' ', $commands);
    }

    public function run()
    {
        $process = Process::fromShellCommandline($this->getCommand());

        $output = '';
        $error = '';

        $code = $process->run(function ($type, $buffer) use (&$output, &$error) {
            if (Process::ERR === $type) {
                $error .= $buffer;
            } else {
                $output .= $buffer;
            }
        });

        return new CommandResponse($process, $code, $output, $error);
    }
}