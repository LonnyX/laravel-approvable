<?php

namespace Lonnyx\Approvable\Contracts;

interface ApproveDriver
{
    /**
     * Perform an audit.
     *
     * @param $model
     * @return \Lonnyx\Approvable\Models\Audit
     */
    public function audit($model);
}
