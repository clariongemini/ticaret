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
    protected static function bootTenantAware()
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
}
