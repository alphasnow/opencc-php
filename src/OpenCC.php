<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:24
 */

namespace AlaphaSnow\OpenCC;


class OpenCC
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function config($config)
    {
        $this->command->config($config);
        return $this;
    }

    public function convert($word, $config = null)
    {
        if(!is_null($config)){
            $this->command->config($config);
        }

        $tmpInput = tempnam(sys_get_temp_dir(), "OPENCC");
        $tmpOutput = tempnam(sys_get_temp_dir(), "OPENCC");
        file_put_contents($tmpInput, $word);
        $this->command->input($tmpInput)->output($tmpOutput);

        $this->command->run();

        $outputData = file_get_contents($tmpOutput);
        unlink($tmpOutput);
        unlink($tmpInput);

        return $outputData;
    }

}