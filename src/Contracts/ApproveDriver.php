<?php

namespace LonnyX\Approvable\Contracts;

interface ApproveDriver
{
    /**
     * Perform an audit.
     *
     * @param \LonnyX\Approvable\Contracts\Approvable $model
     *
     * @return \LonnyX\Approvable\Models\Audit
     */
    public function audit(Approvable $model);
}
