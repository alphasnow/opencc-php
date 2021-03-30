# opencc-php
## 介绍
中文简繁转换开源项目，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、臺湾、香港）。  

## 安装
### opencc
* ubuntu `apt install opencc`  
* CentOs `yum install opencc`  
* Window x32 [下载](https://ci.appveyor.com/api/projects/Carbo/opencc/artifacts/OpenCC.zip?branch=master&job=Environment:%20nodejs_version=none;%20Platform:%20x86)
* Window x64 [下载](https://ci.appveyor.com/api/projects/Carbo/opencc/artifacts/OpenCC.zip?branch=master&job=Environment:%20nodejs_version=none;%20Platform:%20x64)
  
> https://github.com/BYVoid/OpenCC/wiki/Download

### opencc-php
使用`Composer`安装
```shell
composer require alaphasnow/opencc-php
```

## 配置与实例
### Laravel 应用
1. (Laravel5.5+ 忽略)在 `config/app.php` 注册 ServiceProvider 和 Facade 
    ```php
    [
        'providers' => [
            // ...
            AlaphaSnow\OpenCC\ServiceProvider::class,
        ],
        'aliases' => [
            // ...
            'OpenCC' => AlaphaSnow\OpenCC\Facade::class,
        ]
    ];
    ```
2. 发布配置文件：

    ```shell
    php artisan vendor:publish --provider="AlaphaSnow\OpenCC\ServiceProvider"
    ```
    
3. 修改应用根目录下的 `config/opencc.php` 中对应的参数即可。
    ```php
    return [
        'binary_path'=>'/usr/bin/opencc', // 执行文件的路径,默认:/usr/bin/opencc
        'config_path'=>'/usr/share/opencc',// 配置文件的路径,默认:/usr/share/opencc,Ubuntu:/usr/lib/x86_64-linux-gnu/opencc
    ];
    ```

## 使用
### Laravel 应用
```php
// laravel应用可用外观
$result = \OpenCC::convert('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');

print_r($result);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。
```

### 其他应用
```php
use AlaphaSnow\OpenCC\Command;
use AlaphaSnow\OpenCC\OpenCC;
$command = new Command('/usr/bin/opencc','/usr/share/opencc');
$opencc = new OpenCC($command);
$result = $opencc->convert('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');
print_r($result);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。
```

## 备注
如需PHP扩展,可使用[opencc4php](https://github.com/nauxliu/opencc4php)