# opencc-php
## 介绍
中文简繁转换开源项目，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、臺湾、香港）。

## 安装
### opencc
* ubuntu `apt install opencc`
* CentOs `yum install opencc`
[OpenCC](https://github.com/BYVoid/OpenCC)

### opencc-php
使用`Composer`安装
```shell
# php^7.0 laravel^5.5
composer require sleep-cat/opencc-php:^3.0
# php^7.1 laravel^5.8
composer require sleep-cat/opencc-php:^3.1
# php^7.2 laravel^6.0
composer require sleep-cat/opencc-php:^3.2
```

## 配置与实例
### Laravel 应用

1. 在 `config/app.php` 注册 ServiceProvider 和 Facade (Laravel 5.5 + 无需手动注册)
    ```php
    'providers' => [
        // ...
        SleepCat\OpenCC\ServiceProvider::class,
    ],
    'aliases' => [
        // ...
        'OpenCC' => SleepCat\OpenCC\Facade::class,
    ],
    ```
2. 创建配置文件：

    ```shell
    php artisan vendor:publish --provider="SleepCat\OpenCC\ServiceProvider"
    ```
    
3. 修改应用根目录下的 `config/opencc.php` 中对应的参数即可。
    ```php
    return [
        'binary_path'=>'/usr/bin/opencc', // 执行文件的路径,默认:/usr/bin/opencc
        'config_path'=>'/usr/share/opencc',// 配置文件的路径,默认:/usr/share/opencc
    ];
    ```
4. 创建OpenCC实例
    ```php
    $opencc = app()->make('opencc');
    ```
### 其他应用
1. 创建OpenCC实例
    ```php
    use SleepCat\OpenCC\Command;
    use SleepCat\OpenCC\OpenCC;
    $command = new Command('/usr/bin/opencc','/usr/share/opencc');
    $opencc = new OpenCC($command);
    ```

## 使用
```php
// laravel应用可用外观
$result = \OpenCC::transform('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');

// 其他应用使用实例
$result = $opencc->transform('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');

print_r($result);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。
```
## 配置文件
* s2t.json Simplified Chinese to Traditional Chinese 簡體到繁體
* t2s.json Traditional Chinese to Simplified Chinese 繁體到簡體
* s2tw.json Simplified Chinese to Traditional Chinese (Taiwan Standard) 簡體到臺灣正體
* tw2s.json Traditional Chinese (Taiwan Standard) to Simplified Chinese 臺灣正體到簡體
* s2hk.json Simplified Chinese to Traditional Chinese (Hong Kong Standard) 簡體到香港繁體（香港小學學習字詞表標準）
* hk2s.json Traditional Chinese (Hong Kong Standard) to Simplified Chinese 香港繁體（香港小學學習字詞表標準）到簡體
* s2twp.json Simplified Chinese to Traditional Chinese (Taiwan Standard) with Taiwanese idiom 簡體到繁體（臺灣正體標準）並轉換爲臺灣常用詞彙
* tw2sp.json Traditional Chinese (Taiwan Standard) to Simplified Chinese with Mainland Chinese idiom 繁體（臺灣正體標準）到簡體並轉換爲中國大陸常用詞彙
* t2tw.json Traditional Chinese (OpenCC Standard) to Taiwan Standard 繁體（OpenCC 標準）到臺灣正體
* t2hk.json Traditional Chinese (OpenCC Standard) to Hong Kong Standard 繁體（OpenCC 標準）到香港繁體（香港小學學習字詞表標準）
