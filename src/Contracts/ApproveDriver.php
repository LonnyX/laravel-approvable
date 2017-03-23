<?php

namespace Lonnyx\Approvable\Contracts;

interface ApproveDriver
{
    /**
     * Perform an audit.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable $model
     *
     * @return \Lonnyx\Approvable\Models\Audit
     */
    public function audit(Approvable $model);
}
