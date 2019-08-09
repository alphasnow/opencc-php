<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 16:04
 */

namespace SleepCat\OpenCC\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $resources;
    protected $outputs;
    protected $binary;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->resources = RESOURCES_PATH;
        $this->outputs = OUTPUTS_PATH;
        $this->binary = OPENCC_BINARY;
    }

    public function setUp()
    {
        foreach (scandir($this->outputs, SCANDIR_SORT_NONE) as $file) {
            if ('.' !== $file && '..' !== $file && '.gitkeep' !== $file) {
                unlink($this->outputs.'/'.$file);
            }
        }
    }
}
