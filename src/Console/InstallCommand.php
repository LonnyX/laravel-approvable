<?php

namespace Lonnyx\Approvable\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Lonnyx\Approvable\ApprovableServiceProvider;

class InstallCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'approvable:install';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Install the Laravel laravel-approvable package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Publishing the config files');
        Artisan::call('vendor:publish', [
            '--provider' => ApprovableServiceProvider::class,
        ]);

        $this->info('Publishing the migration file');
        Artisan::call('approvable:table');

        $this->info('Successfully installed Laravel Approvable! Enjoy :)');
    }
}
