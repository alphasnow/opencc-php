<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:58
 */
namespace SleepCat\OpenCC\Test;


use SleepCat\OpenCC\Command;

class CommandTest extends TestCase
{
    public function provideTestCommandString()
    {
        yield [$this->resources.'/traditional.txt', $this->outputs.'/simplified.txt', 'zht2zhs.ini'];
        yield [$this->resources.'/simplified.txt', $this->outputs.'/traditional.txt', 'zhs2zht.ini'];
    }

    /**
     * @dataProvider provideTestCommandString
     */
    public function testCommandString($input, $output, $config)
    {
        $command = new Command($this->binary);

        $commandString = $command
            ->input($input)
            ->output($output)
            ->config($config)
            ->getCommand()
        ;

        $expected = $this->binary.
            ' --input "'.$input.'"'.
            ' --output "'.$output.'"'.
            ' --config "'.$config.'"';

//        $expected = str_replace('\\', '/', $expected);

        $this->assertEquals($expected, $commandString);
    }
}