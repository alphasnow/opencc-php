<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace AlaphaSnow\OpenCC;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigFile() => config_path('opencc.php'),
            ], 'config');
        }
        $this->mergeConfigFrom($this->getConfigFile(), 'opencc');
    }
    public function register()
    {
        // class
        $this->app->singleton(Command::class, function ($app) {
            $config = $app->get('config')->get('opencc');
            $command = new Command($config['binary_path'], $config['config_path']);
            $command->setProcessConfig($config['process']);
            return $command;
        });

        $this->app->singleton(OpenCC::class, function ($app) {
            return new OpenCC($app->get('opencc.command'));
        });

        // alias
        $this->app->alias(OpenCC::class, 'opencc');
        $this->app->alias(Command::class, 'opencc.command');
    }

    private function getConfigFile(): string
    {
        return realpath(__DIR__ . '/config.php');
    }

    public function provides()
    {
        return [
            OpenCC::class,
            Command::class
        ];
    }
}
