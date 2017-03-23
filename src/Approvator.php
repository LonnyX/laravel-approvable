<?php

namespace Lonnyx\Approvable;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Lonnyx\Approvable\Contracts\Approvable as ApprovableContract;
use Lonnyx\Approvable\Contracts\ApproveDriver;
use Lonnyx\Approvable\Contracts\Approvator as ApprovatorContract;
use Lonnyx\Approvable\Drivers\Database;
use RuntimeException;

class Approvator extends Manager
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['approvable.default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function createDriver($driver)
    {
        try {
            return parent::createDriver($driver);
        } catch (InvalidArgumentException $exception) {
            if (class_exists($driver)) {
                return $this->app->make($driver);
            }

            throw $exception;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function approveDriver($model)
    {
        $driver = $this->driver($model->getApproveDriver());

        if (!$driver instanceof ApproveDriver) {
            throw new RuntimeException('The driver must implement the AuditDriver contract');
        }

        return $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($model)
    {
        if (!$model->readyForApproving()) {
            return;
        }

        $driver = $this->approveDriver($model);

        if (!$this->fireAuditingEvent($model, $driver)) {
            return;
        }

        $audit = $driver->audit($model);

        $this->app->make('events')->fire(
            new Events\Approved($model, $driver, $audit)
        );
    }

    /**
     * Create an instance of the Database audit driver.
     *
     * @return \Lonnyx\Approvable\Drivers\Database
     */
    protected function createDatabaseDriver()
    {
        return $this->app->make(Database::class);
    }

    /**
     * Fire the laravel-approvable event.
     *
     * @param \Lonnyx\Approvable\Contracts\Approvable   $model
     * @param \Lonnyx\Approvable\Contracts\ApproveDriver $driver
     *
     * @return bool
     */
    protected function fireAuditingEvent($model, ApproveDriver $driver)
    {
        return $this->app->make('events')->until(
            new Events\Approving($model, $driver)
        ) !== false;
    }
}
