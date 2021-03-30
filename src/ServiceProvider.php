<?php
/**
 * Created by PhpStorm.
 * User: Wind91@foxmail.com
 * Date: 2019/8/9
 * Time: 13:41
 */

namespace AlphaSnow\OpenCC;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigFile() => config_path('opencc.php'),
            ], 'config');
        }
        $this->mergeConfigFrom($this->getConfigFile(), 'opencc');
    }

    /**
     * @return void
     */
    public function register()
    {
        // class
        $this->app->singleton(Command::class, function ($app) {
            $config = $app['config']->get('opencc');
            $command = new Command($config['binary_path'], $config['config_path']);
            $command->setProcessConfig($config['process']);
            return $command;
        });

        $this->app->singleton(OpenCC::class, function ($app) {
            return new OpenCC($app[Command::class]);
        });

        // alias
        $this->app->alias(OpenCC::class, 'opencc');
        $this->app->alias(Command::class, 'opencc.command');
    }

    /**
     * @return string
     */
    protected function getConfigFile(): string
    {
        return realpath(__DIR__ . '/config.php');
    }

    /**
     * @return array|string[]
     */
    public function provides()
    {
        return [
            OpenCC::class,
            Command::class
        ];
    }
}
