<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:32
 */

namespace SleepCat\OpenCC\Test;


use SleepCat\OpenCC\Command;
use SleepCat\OpenCC\OpenCC;

class OpenCCTest extends TestCase
{
    public function testTransform()
    {
        $command = new Command($this->binary);
        $openCC = new OpenCC($command);
        $transformString = $openCC->transform('寻寻觅觅，冷冷清清，凄凄惨惨戚戚。','s2t.json');

        $expected = '尋尋覓覓，冷冷清清，悽悽慘慘慼戚。';
        $this->assertSame($expected,$transformString);
    }
}