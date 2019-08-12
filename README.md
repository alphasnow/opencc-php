# opencc-php
## 介绍
中文簡繁轉換開源項目，支持詞彙級別的轉換、異體字轉換和地區習慣用詞轉換（中國大陸、臺灣、香港）。

## 安装
### opencc
[https://github.com/BYVoid/OpenCC](https://github.com/BYVoid/OpenCC)

### opencc-php
使用`Composer`安装
```shell
composer require sleep-cat/opencc-php
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
    $opencc->transform()
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
$result = OpenCC::transform('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json')

// 其他应用使用实例
$result = $opencc->transform('天氣乍涼人寂寞，光陰須得酒消磨。且來花裏聽笙歌。','t2s.json');

print_r($result);
// 天气乍凉人寂寞，光阴须得酒消磨。且来花里听笙歌。
```