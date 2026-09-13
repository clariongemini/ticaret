<?php

namespace App\Models\Scopes;

use App\SharedKernel\Context\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * @param Builder<Model> $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if (TenantContext::getTenantId()) {
            $builder->where($model->getTable() . '.tenant_id', TenantContext::getTenantId());
        }
    }
}
