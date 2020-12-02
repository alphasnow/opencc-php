<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 17:32
 */

namespace AlaphaSnow\OpenCC\Test;


use AlaphaSnow\OpenCC\Command;
use AlaphaSnow\OpenCC\OpenCC;

class OpenCCTest extends TestCase
{
    public function testSimple()
    {
        $command = new Command($this->binaryFile,$this->configPath);
        $openCC = new OpenCC($command);

        $result = $openCC->transform('四面垂杨十里荷。问云何处最花多。画楼南畔夕阳和。','s2t.json');
        $expected = '四面垂楊十里荷。問云何處最花多。畫樓南畔夕陽和。';
        $this->assertSame($expected,$result);
    }

    public function testTraditional()
    {
        $command = new Command($this->binaryFile,$this->configPath);
        $openCC = new OpenCC($command);

        $result = $openCC->transform('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');
        $expected = '天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。';
        $this->assertSame($expected,$result);
    }
}