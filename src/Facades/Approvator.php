<?php

namespace Lonnyx\Approvable\Facades;

use Illuminate\Support\Facades\Facade;

class Approvator extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Lonnyx\Approvable\Contracts\Approvator::class;
    }
}
