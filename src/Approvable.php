<?php

namespace Lonnyx\Approvable;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Lonnyx\Approvable\Contracts\UserResolver;
use Lonnyx\Approvable\Models\Approval as AprovalModel;
use RuntimeException;
use UnexpectedValueException;

trait Approvable
{
    public $FIRE_APPROVABLE_EVENT = true;
    /**
     *  laravel-approvable attribute exclusions.
     *
     * @var array
     */
    protected $approvableExclusions = [];

    /**
     * Approve event name.
     *
     * @var string
     */
    protected $approveEvent;

    /**
     * laravel-approvable boot logic.
     *
     * @return void
     */
    public static function bootApprovable()
    {
        if (static::isApprovableEnabled()) {
            static::observe(new ApprovableObserver());
        }
    }

    /**
     * laravel-approvable Model audits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function approvals()
    {
        return $this->morphMany(AprovalModel::class, 'approvable')
            ->orderBy('created_at', 'DESC');
    }

    /**
     * Update excluded audit attributes.
     *
     * @return void
     */
    protected function updateApproveExclusions()
    {
        $this->approvableExclusions = $this->getApproveExclude();

        // When in strict mode, hidden and non visible attributes are excluded
        if ($this->getApproveStrict()) {
            $this->approvableExclusions = array_merge($this->approvableExclusions, $this->hidden);

            if (count($this->visible)) {
                $invisible = array_diff(array_keys($this->attributes), $this->visible);
                $this->approvableExclusions = array_merge($this->approvableExclusions, $invisible);
            }
        }

        if (!$this->getApproveTimestamps()) {
            array_push($this->approvableExclusions, static::CREATED_AT, static::UPDATED_AT);

            $this->approvableExclusions[] = defined('static::DELETED_AT') ? static::DELETED_AT : 'deleted_at';
        }

        $attributes = array_except($this->attributes, $this->approvableExclusions);

        foreach ($attributes as $attribute => $value) {
            // Apart from null, non scalar values will be excluded
            if (is_object($value) && !method_exists($value, '__toString') || is_array($value)) {
                $this->approvableExclusions[] = $attribute;
            }
        }
    }

    /**
     * Set the old/new attributes corresponding to an updated event.
     *
     * @param array $old
     * @param array $new
     *
     * @return void
     */
    protected function approveUpdatingAttributes(array &$old, array &$new)
    {
        $old = $this->approvals()->latest()->first();
        if(empty($old))
            $old = $this->getOriginal();
        else
            $old = $old->new_values;

        $new = $this->getAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function readyForApproving()
    {
        return $this->isEventApprovable($this->approveEvent);
    }

    /**
     * {@inheritdoc}
     */
    public function toApprove()
    {
        if (!$this->readyForApproving()) {
            throw new RuntimeException('A valid audit event has not been set');
        }

        $method = 'approve'.Str::studly($this->approveEvent).'Attributes';

        if (!method_exists($this, $method)) {
            throw new RuntimeException(sprintf(
                'Unable to handle "%s" event, %s() method missing',
                $this->approveEvent,
                $method
            ));
        }

        $this->updateApproveExclusions();

        $old = [];
        $new = [];

        $this->{$method}($old, $new);

        return $this->transformApprove([
            'old_values'     => $old,
            'new_values'     => $new,
            'event'          => $this->approveEvent,
            'approvable_id'   => $this->getKey(),
            'approvable_type' => $this->getMorphClass(),
            'user_id'        => $this->resolveUserId(),
            'created_at'     => $this->freshTimestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function transformApprove(array $data)
    {
        return $data;
    }

    /**
     * Resolve the ID of the logged User.
     *
     * @throws UnexpectedValueException
     *
     * @return mixed|null
     */
    protected function resolveUserId()
    {
        $userResolver = Config::get('audit.user.resolver');

        if (is_callable($userResolver)) {
            return $userResolver();
        }

        if (is_subclass_of($userResolver, UserResolver::class)) {
            return call_user_func([$userResolver, 'resolveId']);
        }

        throw new UnexpectedValueException('Invalid User resolver, callable or UserResolver FQCN expected');
    }

    /**
     * Resolve the current request URL if available.
     *
     * @return string
     */
    protected function resolveUrl()
    {
        if (App::runningInConsole()) {
            return 'console';
        }

        return Request::fullUrl();
    }

    /**
     * Resolve the current IP address.
     *
     * @return string
     */
    protected function resolveIpAddress()
    {
        return Request::ip();
    }

    /**
     * Determine if an attribute is eligible for auditing.
     *
     * @param string $attribute
     *
     * @return bool
     */
    protected function isAttributeApprovable($attribute)
    {
        // The attribute should not be audited
        if (in_array($attribute, $this->approvableExclusions)) {
            return false;
        }

        // The attribute is auditable when explicitly
        // listed or when the include array is empty
        $include = $this->getApproveInclude();

        return in_array($attribute, $include) || empty($include);
    }

    /**
     * Determine whether an event is auditable.
     *
     * @param string $event
     *
     * @return bool
     */
    protected function isEventApprovable($event)
    {
        return in_array($event, $this->getApprovableEvents());
    }

    /**
     * {@inheritdoc}
     */
    public function setApproveEvent($event)
    {
        $this->approveEvent = $this->isEventApprovable($event) ? $event : null;

        return $this;
    }

    /**
     * Get the auditable events.
     *
     * @return array
     */
    public function getApprovableEvents()
    {
        if (isset($this->approvableEvents)) {
            return $this->approvableEvents;
        }

        return [
            'creating',
            'updating',
            'deleting',
            'restoring',
        ];
    }

    /**
     * Determine whether auditing is enabled.
     *
     * @return bool
     */
    public static function isApprovableEnabled()
    {
        if (App::runningInConsole()) {
            return (bool) Config::get('audit.console', false);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveInclude()
    {
        return isset($this->approveInclude) ? (array) $this->approveInclude : [];
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveExclude()
    {
        return isset($this->approveExclude) ? (array) $this->approveExclude : [];
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveStrict()
    {
        return isset($this->approveStrict) ? (bool) $this->approveStrict : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveTimestamps()
    {
        return isset($this->approveTimestamps) ? (bool) $this->approveTimestamps : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveDriver()
    {
        return isset($this->approveDriver) ? $this->approveDriver : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getApproveThreshold()
    {
        return isset($this->approveThreshold) ? $this->approveThreshold : 0;
    }

    /**
     * The condition to check before triggering the Approvator
     * if this return true, the changes will be recorded to the DB
     * and the model will not update.
     *
     * @return boolean
     */
    public function approvalCondition()
    {
        return true;
    }

    public function markApproved()
    {
        $this->FIRE_APPROVABLE_EVENT = false;
        $last = $this->approvals()->latest()->first();
        $last->update(['status' => 1]);
        $this->update($last->new_values);
    }

    public function previsualize()
    {
        $last = $this->approvals()->latest()->first();
        return $this->fill($last->new_values);
    }
}
