<?php

namespace LonnyX\Approvable\Drivers;

use LonnyX\Approvable\Contracts\Approvable;
use LonnyX\Approvable\Contracts\ApproveDriver;
use LonnyX\Approvable\Models\Approval;

class Database implements ApproveDriver
{
    /**
     * {@inheritdoc}
     */
    public function audit(Approvable $model)
    {
        return Approval::create($model->toApprove());
    }
}
