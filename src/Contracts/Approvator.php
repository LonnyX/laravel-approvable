<?php

namespace Lonnyx\Approvable\Contracts;

interface Approvator
{
    /**
     * Get an audit driver instance.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable $model
     *
     * @return ApproveDriver
     */
    public function approveDriver(Approvable $model);

    /**
     * Perform an audit.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable $model
     *
     * @return void
     */
    public function execute(Approvable $model);
}
