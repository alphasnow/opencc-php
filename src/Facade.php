<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace AlaphaSnow\OpenCC;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class OpenCCFacade
 * @package AlaphaSnow\OpenCC
 */
class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'opencc';
    }
}