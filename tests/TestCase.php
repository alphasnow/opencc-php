<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 16:04
 */

namespace AlphaSnow\OpenCC\Tests;

use AlphaSnow\OpenCC\Facade;
use AlphaSnow\OpenCC\ServiceProvider;
use Orchestra\Testbench\TestCase  as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $files;
    protected $outputs;
    protected $binaryFile;
    protected $configPath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->files = FILES_PATH;
        $this->outputs = OUTPUTS_PATH;
        $this->binaryFile = OPENCC_BINARY;
        $this->configPath = OPENCC_CONFIG;
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return ['OpenCC' => Facade::class];
    }

    public function setUp()
    {
        parent::setUp();

        // clear outputs
        foreach (scandir($this->outputs, SCANDIR_SORT_NONE) as $file) {
            if ('.' !== $file && '..' !== $file && '.gitkeep' !== $file) {
                unlink($this->outputs.'/'.$file);
            }
        }
    }
}
