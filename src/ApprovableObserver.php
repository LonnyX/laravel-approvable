<?php

namespace LonnyX\Approvable;

use LonnyX\Approvable\Contracts\Approvable as AuditableContract;
use LonnyX\Approvable\Facades\Approvator;

class ApprovableObserver
{
    /**
     * Handle the updating event for the model.
     *
     * @param \LonnyX\Approvable\Contracts\Approvable $model
     *
     * @return void
     */
    public function updating(AuditableContract $model)
    {
        if($model->approvalCondition() && $model->FIRE_APPROVABLE_EVENT){
            Approvator::execute($model->setApproveEvent('updating'));
            return false;
        }

        return true;
    }
}
