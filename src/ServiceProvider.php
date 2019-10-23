<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace SleepCat\OpenCC;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if ($this->app instanceof LumenApplication) {
                $this->app->configure('excel');
            } else {
                $this->publishes([
                    $this->getConfigFile() => config_path('opencc.php'),
                ], 'config');
            }
        }
    }
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'opencc');

        // class
        $this->app->singleton('opencc.command', function ($app) {
            $options = $app['config']->get('opencc');

            return new Command($options['binary_path']);
        });

        $this->app->singleton('opencc', function ($app) {
            return new OpenCC($app['opencc.command']);
        });

        // alias
        $this->app->alias('opencc', OpenCC::class);
        $this->app->alias('opencc.command', Command::class);
    }

    private function getConfigFile(): string
    {
        return realpath(__DIR__ . '/config.php');
    }
}
