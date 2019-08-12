<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace SleepCat\OpenCC;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        // config
        $source = realpath(__DIR__ . '/config.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('opencc.php')], 'opencc');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('opencc');
        }
        $this->mergeConfigFrom($source, 'opencc');

        // class
        $this->app->singleton('opencc.command', function ($app) {
            $options = $app['config']->get('opencc');

            return new Command($options['binary']);
        });

        $this->app->singleton('opencc', function ($app) {
            return new OpenCC($app['opencc']);
        });

        // alias
        $this->app->alias('opencc', OpenCC::class);
        $this->app->alias('opencc.command', Command::class);
    }
}