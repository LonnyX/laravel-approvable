<?php

namespace LonnyX\Approvable\Contracts;

interface Approvator
{
    /**
     * Get an audit driver instance.
     *
     * @param \LonnyX\Approvable\Contracts\Approvable $model
     *
     * @return ApproveDriver
     */
    public function approveDriver(Approvable $model);

    /**
     * Perform an audit.
     *
     * @param \LonnyX\Approvable\Contracts\Approvable $model
     *
     * @return void
     */
    public function execute(Approvable $model);
}
