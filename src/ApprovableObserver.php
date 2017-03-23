<?php

namespace Lonnyx\Approvable;

use Lonnyx\Approvable\Contracts\Approvable as AuditableContract;
use Lonnyx\Approvable\Facades\Approvator;

class ApprovableObserver
{
    /**
     * Handle the updating event for the model.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable $model
     *
     * @return void
     */
    public function updating($model)
    {
        if($model->approvalCondition() && $model->FIRE_APPROVABLE_EVENT){
            Approvator::execute($model->setApproveEvent('updating'));
            return false;
        }

        return true;
    }
}
