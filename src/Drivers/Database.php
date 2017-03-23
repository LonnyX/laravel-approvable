<?php

namespace Lonnyx\Approvable\Drivers;

use Lonnyx\Approvable\Contracts\Approvable;
use Lonnyx\Approvable\Contracts\ApproveDriver;
use Lonnyx\Approvable\Models\Approval;

class Database implements ApproveDriver
{
    /**
     * {@inheritdoc}
     */
    public function audit($model)
    {
        return Approval::create($model->toApprove());
    }
}
