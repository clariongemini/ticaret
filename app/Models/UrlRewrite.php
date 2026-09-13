<?php

namespace App\Models;

use App\Exceptions\TenantIsolationException;
use App\Models\Traits\TenantAware;
use App\SharedKernel\Context\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UrlRewrite extends BaseModel
{
    use TenantAware;

    protected $fillable = [
        'locale',
        'slug',
        'target_id',
        'target_type',
        'is_active',
        'redirect_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot: enforce cross-tenant target validation on creation.
     * Since target is polymorphic, DB-level FK is impossible.
     * We enforce at application layer that target belongs to the same tenant.
     */
    protected static function booted(): void
    {
        static::creating(function (UrlRewrite $rewrite) {
            if (TenantContext::isSystemContext()) {
                return;
            }

            // Resolve the target model and verify tenant ownership
            $targetClass = $rewrite->target_type;
            if (!$targetClass || !$rewrite->target_id) {
                return;
            }

            /** @var Model|null $target */
            $target = $targetClass::withoutGlobalScopes()->find($rewrite->target_id);

            if (!$target) {
                throw new TenantIsolationException(
                    "UrlRewrite target [{$rewrite->target_type}:{$rewrite->target_id}] does not exist."
                );
            }

            if (!isset($target->tenant_id)) {
                return; // Non-tenant-aware targets are allowed (e.g. global resources)
            }

            if ($target->tenant_id !== TenantContext::getTenantId()) {
                throw new TenantIsolationException(
                    "Cross-tenant UrlRewrite is blocked: target belongs to tenant [{$target->tenant_id}], " .
                    "current tenant is [" . TenantContext::getTenantId() . "]."
                );
            }
        });
    }

    /**
     * Get the target entity this rewrite points to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<Model, $this>
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
