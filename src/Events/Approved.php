<?php

namespace LonnyX\Approvable\Events;

use LonnyX\Approvable\Contracts\Approvable;
use LonnyX\Approvable\Contracts\ApproveDriver;
use LonnyX\Approvable\Models\Approval;

class Approved
{
    /**
     * The Auditable model.
     *
     * @var \LonnyX\Approvable\Contracts\Approvable
     */
    public $model;

    /**
     * Audit driver.
     *
     * @var \LonnyX\Approvable\Contracts\ApproveDriver
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
     * @param \LonnyX\Approvable\Contracts\Approvable   $model
     * @param \LonnyX\Approvable\Contracts\ApproveDriver $driver
     * @param \LonnyX\Approvable\Models\Approval          $approval
     */
    public function __construct(Approvable $model, ApproveDriver $driver, Approval $approval = null)
    {
        $this->model = $model;
        $this->driver = $driver;
        $this->report = $approval;
    }
}
