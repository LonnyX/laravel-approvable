<?php

namespace LonnyX\Approvable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class ApproveTableCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'approvable:table';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a migration for the approvals table';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Composer.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $fullPath = $this->createBaseMigration();

        $this->files->put($fullPath, $this->files->get(__DIR__.'/stubs/approvals.stub'));

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the audits.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = 'create_approvals_table';

        $path = $this->laravel->databasePath().'/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }
}
