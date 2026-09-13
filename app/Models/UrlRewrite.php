<?php

namespace App\Models;

use App\Models\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UrlRewrite extends Model
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
     * Get the target entity this rewrite points to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<Model, $this>
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
