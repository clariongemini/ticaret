<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;

trait TenantAware
{
    /**
     * Boot the tenant scope for this model.
     */
    protected static function bootTenantAware()
    {
        static::addGlobalScope(new TenantScope);
    }
}
