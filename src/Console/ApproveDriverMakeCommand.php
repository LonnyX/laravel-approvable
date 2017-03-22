<?php

namespace LonnyX\Approvable\Console;

use Illuminate\Console\GeneratorCommand;

class ApproveDriverMakeCommand extends GeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'make:approve-driver';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new approve driver';

    /**
     * {@inheritdoc}
     */
    protected $type = 'ApproveDriver';

    /**
     * {@inheritdoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/driver.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ApproveDrivers';
    }
}
