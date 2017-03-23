<?php

namespace Lonnyx\Approvable\Contracts;

interface UserResolver
{
    /**
     * Resolve the ID of the logged User.
     *
     * @return mixed|null
     */
    public static function resolveId();
}
