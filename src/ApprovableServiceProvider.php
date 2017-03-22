<?php

namespace LonnyX\Approvable;

use Illuminate\Support\ServiceProvider;
use LonnyX\Approvable\Console\ApproveDriverMakeCommand;
use LonnyX\Approvable\Console\ApproveTableCommand;
use LonnyX\Approvable\Console\InstallCommand;
use LonnyX\Approvable\Contracts\Approvator;

class ApprovableServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $defer = true;

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }

    /**
     * Setup the config.
     *
     * @param $app
     *
     * @return void
     */
    protected function setupConfig($app)
    {
        $config = realpath(__DIR__.'/../config/audit.php');

        if ($app->runningInConsole()) {
            $this->publishes([
                $config => base_path('config/audit.php'),
            ]);
        }

        $this->mergeConfigFrom($config, 'audit');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ApproveTableCommand::class,
            ApproveDriverMakeCommand::class,
            InstallCommand::class,
        ]);

        $this->app->singleton(Approvator::class, function ($app) {
            return new \LonnyX\Approvable\Approvator($app);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            Approvator::class,
        ];
    }
}
