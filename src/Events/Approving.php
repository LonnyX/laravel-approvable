<?php

namespace LonnyX\Approvable\Events;

use LonnyX\Approvable\Contracts\Approvable;
use LonnyX\Approvable\Contracts\ApproveDriver;

class Approving
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
     * Create a new laravel-approvable event instance.
     *
     * @param \LonnyX\Approvable\Contracts\Approvable   $model
     * @param \LonnyX\Approvable\Contracts\ApproveDriver $driver
     */
    public function __construct(Approvable $model, ApproveDriver $driver)
    {
        $this->model = $model;
        $this->driver = $driver;
    }
}
