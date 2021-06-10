# opencc-php
## 介绍
[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphasnow/opencc-php.svg?style=flat-square)](https://packagist.org/packages/alphasnow/opencc-php)
[![Total Downloads](https://img.shields.io/packagist/dt/alphasnow/opencc-php.svg?style=flat-square)](https://packagist.org/packages/alphasnow/opencc-php)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

中文简繁转换开源项目，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、臺湾、香港）。  

## 安装
1. 第一步,在服务器安装`opencc`
    * Ubuntu `apt install opencc`  
    * CentOS `yum install opencc opencc-tools`  
    * Window x32 [下载](https://ci.appveyor.com/api/projects/Carbo/opencc/artifacts/OpenCC.zip?branch=master&job=Environment:%20nodejs_version=none;%20Platform:%20x86)
    * Window x64 [下载](https://ci.appveyor.com/api/projects/Carbo/opencc/artifacts/OpenCC.zip?branch=master&job=Environment:%20nodejs_version=none;%20Platform:%20x64)
      
    > https://github.com/BYVoid/OpenCC/wiki/Download

2. 第二步,使用`Composer`安装`opencc-php`
    ```bash
    composer require alphasnow/opencc-php
    ```

## 应用配置

### Laravel应用
1. (Laravel5.5+ 忽略)在 `config/app.php` 注册 ServiceProvider 和 Facade 
    ```php
    [
        'providers' => [
            // ...
            AlphaSnow\OpenCC\ServiceProvider::class,
        ],
        'aliases' => [
            // ...
            'OpenCC' => AlphaSnow\OpenCC\Facade::class,
        ]
    ];
    ```
2. 发布配置文件

    ```bash
    php artisan vendor:publish --provider="AlphaSnow\OpenCC\ServiceProvider"
    ```
    
3. 修改配置文件 `config/opencc.php`
    ```php
    return [
        // 执行文件的路径,默认:/usr/bin/opencc
        'binary_path'=> env('OPENCC_BINARY', '/usr/bin/opencc'),
        // 预设配置文件夹的路径,默认:/usr/share/opencc
        'config_path'=> env('OPENCC_CONFIG', '/usr/share/opencc'),
    ];
    ```
   或修改环境配置 `.env`
   ```
   OPENCC_BINARY=/usr/bin/opencc
   OPENCC_CONFIG=/usr/share/opencc
   ```

## 快速使用

### Laravel应用
```php
// laravel应用可用外观
// 参数1:待转换内容, 参数2:预设配置(可省略.json)
$simplified = \OpenCC::convert('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');
print_r($simplified);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。

$traditional = \OpenCC::convert('四面垂杨十里荷。问云何处最花多。画楼南畔夕阳和。', 's2t');
print_r($traditional);
// 四面垂楊十里荷。問云何處最花多。畫樓南畔夕陽和。
```

### 其他应用
```php
use AlphaSnow\OpenCC\Command;
use AlphaSnow\OpenCC\OpenCC;

$command = new Command('/usr/bin/opencc','/usr/share/opencc');
$openCC = new OpenCC($command);

$result = $openCC->convert('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');
print_r($result);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。
```

## 预设配置
- s2t.json Simplified Chinese to Traditional Chinese 简体到繁体
- t2s.json Traditional Chinese to Simplified Chinese 繁体到简体

## 备注说明
- 如需PHP原生扩展, 可使用 [opencc4php](https://github.com/nauxliu/opencc4php).
- Window系统`opencc`配置示例
    ```
    OPENCC_BINARY=D:/OpenCC/build/bin/opencc.exe
    OPENCC_BINARY=D:/OpenCC/build/share/opencc
    ```

## License
See [LICENSE](LICENSE).
