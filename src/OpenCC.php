<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:24
 */

namespace SleepCat\OpenCC;


class OpenCC
{
    protected $command;
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function transform($word,$type='s2t.json')
    {
        $tmpInput = tempnam(sys_get_temp_dir(), "OPENCC");
        $tmpOutput = tempnam(sys_get_temp_dir(), "OPENCC");
        file_put_contents($tmpInput,$word);

        $this->command->input($tmpInput)->output($tmpOutput);
        if($config){
            $config = $this->typeToConfig($type);
            $this->command->config($config);
        }
        $response = $this->command->run();
        
        $outputData = file_get_contents($tmpOutput);
        unlink($tmpOutput);
        unlink($tmpInput);

        return $outputData;
    }
    public function typeToConfig($type)
    {
        return $type;
    }
}