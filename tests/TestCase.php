<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 16:04
 */

namespace AlaphaSnow\OpenCC\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $files;
    protected $outputs;
    protected $binaryFile;
    protected $configPath;

    public function __construct($name = null, array $data = [],$dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->files = FILES_PATH;
        $this->outputs = OUTPUTS_PATH;
        $this->binaryFile = OPENCC_BINARY;
        $this->configPath = OPENCC_CONFIG;
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
