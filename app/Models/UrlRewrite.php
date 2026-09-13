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
     * Boot: enforce cross-tenant target validation on ALL mutation paths.
     *
     * Since target is polymorphic, DB-level FK across tenants is impossible.
     * We enforce at application layer that target belongs to the same tenant
     * on BOTH create AND update (if target_id or target_type is being changed).
     *
     * Bulk target_id mutation is additionally blocked at the builder level.
     */
    protected static function booted(): void
    {
        static::creating(function (UrlRewrite $rewrite) {
            if (TenantContext::isSystemContext()) {
                return;
            }
            self::assertTargetBelongsToCurrentTenant($rewrite->target_type, $rewrite->target_id);
        });

        static::updating(function (UrlRewrite $rewrite) {
            if (TenantContext::isSystemContext()) {
                return;
            }

            // If target_id or target_type is being changed, re-validate tenant ownership
            if ($rewrite->isDirty('target_id') || $rewrite->isDirty('target_type')) {
                self::assertTargetBelongsToCurrentTenant(
                    $rewrite->target_type,
                    $rewrite->target_id
                );
            }
        });
    }

    /**
     * Verify that the given target (by class and id) belongs to the current tenant.
     * Throws TenantIsolationException on cross-tenant attempt.
     */
    private static function assertTargetBelongsToCurrentTenant(?string $targetClass, mixed $targetId): void
    {
        if (!$targetClass || !$targetId) {
            return;
        }

        $targetIdStr = (string) $targetId;

        /** @var Model|null $target */
        $target = $targetClass::withoutGlobalScopes()->find($targetId);

        if (!$target) {
            throw new TenantIsolationException(
                "UrlRewrite target [{$targetClass}:{$targetIdStr}] does not exist."
            );
        }

        // Non-tenant-aware targets (e.g. global resources) are allowed
        if (!property_exists($target, 'tenant_id') && !isset($target->tenant_id)) {
            return;
        }

        if ($target->tenant_id !== TenantContext::getTenantId()) {
            throw new TenantIsolationException(
                "Cross-tenant UrlRewrite is blocked: target belongs to tenant [{$target->tenant_id}], " .
                "current tenant is [" . TenantContext::getTenantId() . "]."
            );
        }
    }

    /**
     * Override builder to block bulk target_id / target_type mutations.
     * These bypass the updating Eloquent event, so they are explicitly prohibited.
     * Any target change must go through model->save() to pass tenant validation.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function newEloquentBuilder($query)
    {
        /** @phpstan-ignore-next-line */
        return new class($query) extends \Illuminate\Database\Eloquent\Builder {
            public function update(array $values)
            {
                if (array_key_exists('target_id', $values) || array_key_exists('target_type', $values)) {
                    throw new \DomainException(
                        "Bulk update of target_id/target_type on url_rewrites is prohibited. " .
                        "Use model->target_id = X; model->save() to ensure tenant isolation."
                    );
                }
                return parent::update($values);
            }
        };
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
