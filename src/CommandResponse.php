<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 14:50
 */

namespace AlphaSnow\OpenCC;

use Symfony\Component\Process\Process;

class CommandResponse
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $error;

    public function __construct(Process $process, int $code, string $output, string $error)
    {
        $this->code = $code;
        $this->output = $output;
        $this->error = $error;
        $this->process = $process;
    }

    public function isSuccessful(): bool
    {
        return 0 === $this->code;
    }

    public function hasFailed(): bool
    {
        return 0 !== $this->code;
    }

    public function getProcess(): Process
    {
        return $this->process;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
