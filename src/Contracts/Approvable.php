<?php

namespace LonnyX\Approvable\Contracts;

interface Approvable
{
    /**
     * Set the Audit event.
     *
     * @param string $event
     *
     * @return Approvable
     */
    public function setApproveEvent($event);

    /**
     * Is the model ready for auditing?
     *
     * @return bool
     */
    public function readyForApproving();

    /**
     * Return data for an Audit.
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function toApprove();

    /**
     * Get the (laravel-approvable) attributes included in approve.
     *
     * @return array
     */
    public function getApproveInclude();

    /**
     * Get the (laravel-approvable) attributes excluded from approve.
     *
     * @return array
     */
    public function getApproveExclude();

    /**
     * Get the strict approve status.
     *
     * @return bool
     */
    public function getApproveStrict();

    /**
     * Get the audit (Auditable) timestamps status.
     *
     * @return bool
     */
    public function getApproveTimestamps();

    /**
     * Get the Approve Driver.
     *
     * @return string
     */
    public function getApproveDriver();

    /**
     * Get the Approve threshold.
     *
     * @return int
     */
    public function getApproveThreshold();

    /**
     * Transform the data before performing an approve.
     *
     * @param array $data
     *
     * @return array
     */
    public function transformApprove(array $data);
}
