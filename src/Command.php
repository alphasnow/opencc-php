<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace AlphaSnow\OpenCC;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use RuntimeException;
use InvalidArgumentException;
use Exception;

class Command
{
    /**
     * @var string
     */
    protected $binaryFile;
    /**
     * @var string
     */
    protected $configPath;
    /**
     * @var string|null
     */
    protected $inputFile;
    /**
     * @var string|null
     */
    protected $outputFile;
    /**
     * @var string|null
     */
    protected $configFile;

    /**
     * @var array
     */
    protected $processConfig = [
        'cwd' => null,
        'env' => null,
        'timeout' => 10,
    ];

    /**
     * @var Process
     */
    protected $process;

    /**
     * Command constructor.
     * @param null|string $binaryFile
     * @param null|string $configPath
     */
    public function __construct($binaryFile = null, $configPath = null)
    {
        !is_null($binaryFile) && $this->binaryFile = $this->cleanPath($binaryFile);
        !is_null($configPath) && $this->configPath = $this->cleanPath($configPath);
    }

    /**
     * @param null $binaryFile
     * @param null $configPath
     * @return Command
     */
    public static function create($binaryFile = null, $configPath = null)
    {
        return new self($binaryFile, $configPath);
    }

    /**
     * @param Process $process
     * @return $this
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
        return $this;
    }

    public function getProcess()
    {
        if ($this->process) {
            return $this->process;
        }
        $process = new Process('');
        if (!is_null($this->processConfig['env'])) {
            $process->setEnv($this->processConfig['env']);
        }
        if (!is_null($this->processConfig['cwd'])) {
            $process->setWorkingDirectory($this->processConfig['cwd']);
        }
        if (!is_null($this->processConfig['timeout'])) {
            $process->setTimeout($this->processConfig['timeout']);
        }
        return $process;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setProcessConfig(array $config)
    {
        $this->processConfig = $config;
        return $this;
    }

    /**
     * @return string|null
     */
    public static function findBinary()
    {
        $binaryFile = (new ExecutableFinder())->find('opencc');

        if ($binaryFile && !is_file($binaryFile)) {
            throw new BinaryNotFoundException($binaryFile);
        }

        if (!is_executable($binaryFile)) {
            throw new InvalidArgumentException(sprintf(
                'The specified script (%s) is not executable.',
                $binaryFile
            ));
        }

        return $binaryFile;
    }

    /**
     * @param string $binaryFile
     */
    public function checkBinaryFile($binaryFile)
    {
        $commands = [$binaryFile, '--version'];
        $process = $this->getProcess()->setCommandLine($commands);

        try {
            $code = $process->run();
        } catch (Exception $e) {
            throw new RuntimeException('Could not check OpenCC version', 1, $e);
        }

        if (0 !== $code || !$process->isSuccessful()) {
            throw new InvalidArgumentException(sprintf(
                "OpenCC does not seem to work well, the test command resulted in an error.\n" .
                "Execution returned message: \"{$process->getExitCodeText()}\"\n" .
                "To solve this issue, please run this command and check your error messages to see if OpenCC was correctly installed:\n%s",
                $binaryFile . ' -version'
            ));
        }

        $process->stop(1);
    }

    /**
     * @param string $configPath
     */
    public function checkConfigPath($configPath)
    {
        if (is_null($configPath) || !is_dir($configPath)) {
            throw new InvalidArgumentException(sprintf(
                "OpenCC config folder doesn't exist:\n%s",
                $configPath
            ));
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private static function cleanPath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = rtrim($path, '/');
        return $path;
    }

    /**
     * @param string $inputFile
     * @return $this
     */
    public function input($inputFile)
    {
        if (!is_file($inputFile)) {
            throw new InvalidArgumentException(sprintf(
                'The input file (%s) is not exist.',
                $inputFile
            ));
        }

        $this->inputFile = $this->cleanPath($inputFile);
        return $this;
    }

    /**
     * @param string $outputFile
     * @return $this
     */
    public function output($outputFile)
    {
        $this->outputFile = $this->cleanPath($outputFile);
        return $this;
    }

    /**
     * @param string $configFile
     * @return $this
     */
    public function config($configFile)
    {
        $this->configFile = $this->cleanPath($configFile);
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        $commands = [
            $this->binaryFile,
            '--input',
            '"' . $this->inputFile . '"',
            '--output',
            '"' . $this->outputFile . '"',
        ];
        if ($this->configFile) {
            $commands[] = '--config';
            $commands[] = '"' . $this->getRealConfigFile() . '"';
        }
        return implode(' ', $commands);
    }

    /**
     * @return string
     */
    protected function getRealConfigFile()
    {
        $filePath = $this->cleanPath($this->configFile);
        if (!$this->configPath) {
            return $filePath;
        }
        if (strpos('/', '$' . $filePath)) {
            return $filePath;
        }
        return rtrim($this->configPath, '/') . '/' . $this->configFile;
    }

    /**
     * @return CommandResponse
     */
    public function run()
    {
        $commands = $this->getCommand();
        $process = $this->getProcess()->setCommandLine($commands);

        $output = $error = '';

        $code = $process->run(function ($type, $buffer) use (&$output, &$error) {
            if (Process::ERR === $type) {
                $error .= $buffer;
            } else {
                $output .= $buffer;
            }
        });

        $process->stop(3);
        $this->inputFile = $this->outputFile = $this->configFile = null;

        return new CommandResponse($process, $code, $output, $error);
    }
}
