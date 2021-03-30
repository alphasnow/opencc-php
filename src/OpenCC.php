<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:24
 */

namespace AlaphaSnow\OpenCC;

use Neutron\TemporaryFilesystem\Manager;

class OpenCC
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * OpenCC constructor.
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @param string $config
     * @return $this
     */
    public function config($config)
    {
        $this->command->config($config);
        return $this;
    }

    /**
     * @param string $word
     * @param null|string $config
     * @return false|string
     */
    public function convert($word, $config = null)
    {
        if (!is_null($config)) {
            $this->command->config($config);
        }

        $filesystem = Manager::create();
        $tmpInput = $filesystem->createTemporaryFile('opencc', '_input', 'txt');
        $tmpOutput = $filesystem->createTemporaryFile('opencc', '_out', 'txt');
        file_put_contents($tmpInput, $word);

        $this->command->input($tmpInput)->output($tmpOutput);
        $this->command->run();

        $outputData = file_get_contents($tmpOutput);
        $filesystem->clean('opencc');

        return $outputData;
    }
}
