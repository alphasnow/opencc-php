<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:32
 */

namespace AlaphaSnow\OpenCC\Tests;

use AlaphaSnow\OpenCC\OpenCC;

class FacadeTest extends TestCase
{
    public function testSimple()
    {
        $opencc = \OpenCC::getFacadeRoot();
        $this->assertInstanceOf(OpenCC::class, $opencc);
    }

    public function testConvert()
    {
        $result = \OpenCC::convert('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。', 't2s.json');
        $this->assertSame('天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。', $result);
    }
}
