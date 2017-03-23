<?php

namespace Lonnyx\Approvable\Events;

use Lonnyx\Approvable\Contracts\Approvable;
use Lonnyx\Approvable\Contracts\ApproveDriver;

class Approving
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
     * Create a new laravel-approvable event instance.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable   $model
     * @param \Lonnyx\Approvable\Contracts\ApproveDriver $driver
     */
    public function __construct(Approvable $model, ApproveDriver $driver)
    {
        $this->model = $model;
        $this->driver = $driver;
    }
}
