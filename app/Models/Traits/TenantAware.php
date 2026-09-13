<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\SharedKernel\Context\TenantContext;
use App\Exceptions\TenantIsolationException;

trait TenantAware
{
    /**
     * Boot the tenant scope and mutation protections for this model.
     */
    protected static function bootTenantAware(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (TenantContext::isSystemContext()) {
                return;
            }

            if (!TenantContext::getTenantId()) {
                throw new TenantIsolationException("Cannot create [{$model->getTable()}] without an active TenantContext.");
            }

            // Auto-inject tenant_id if not present
            if (!isset($model->tenant_id)) {
                $model->tenant_id = TenantContext::getTenantId();
            }

            // Prevent cross-tenant creation
            if ($model->tenant_id !== TenantContext::getTenantId()) {
                throw new TenantIsolationException("Cross-tenant creation is blocked for [{$model->getTable()}].");
            }
        });

        static::updating(function ($model) {
            if (TenantContext::isSystemContext()) {
                return;
            }

            // Prevent tenant_id modification
            if ($model->isDirty('tenant_id')) {
                throw new TenantIsolationException("Modifying tenant_id is blocked for [{$model->getTable()}].");
            }
        });
    }

    /**
     * Create a new Eloquent query builder for the model.
     * Prevents mass update of tenant_id unless in SystemContext.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function newEloquentBuilder($query)
    {
        return new class($query) extends \Illuminate\Database\Eloquent\Builder {
            public function update(array $values)
            {
                if (!\App\SharedKernel\Context\TenantContext::isSystemContext() && array_key_exists('tenant_id', $values)) {
                    throw new \App\Exceptions\TenantIsolationException("Mass updating tenant_id is blocked for [" . $this->model->getTable() . "].");
                }
                return parent::update($values);
            }
        };
    }
}
