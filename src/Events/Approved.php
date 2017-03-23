<?php

namespace Lonnyx\Approvable\Events;

use Lonnyx\Approvable\Contracts\Approvable;
use Lonnyx\Approvable\Contracts\ApproveDriver;
use Lonnyx\Approvable\Models\Approval;

class Approved
{
    /**
     * The Auditable model.
     *
     * @var \Lonnyx\Approvable\Contracts\Approvable
     */
    public $model;

    /**
     * Audit driver.
     *
     * @var \Lonnyx\Approvable\Contracts\ApproveDriver
     */
    public $driver;

    /**
     * The report response.
     *
     * @var mixed
     */
    public $report;

    /**
     * Create a new Audited event instance.
     *
     * @param $model
     * @param \Lonnyx\Approvable\Contracts\ApproveDriver $driver
     * @param \Lonnyx\Approvable\Models\Approval         $approval
     */
    public function __construct($model, ApproveDriver $driver, Approval $approval = null)
    {
        $this->model = $model;
        $this->driver = $driver;
        $this->report = $approval;
    }
}
