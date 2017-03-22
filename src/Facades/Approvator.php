<?php

namespace LonnyX\Approvable\Facades;

use Illuminate\Support\Facades\Facade;

class Approvator extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \LonnyX\Approvable\Contracts\Approvator::class;
    }
}
