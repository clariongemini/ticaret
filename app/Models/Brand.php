<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Traits\TenantAware;
use App\Models\Traits\Translatable;

class Brand extends BaseModel
{
    use TenantAware, Translatable;

    protected $fillable = [
        'name',
        'slug',
        'logo_id',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    /**
     * Get the URL rewrites for the brand.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<UrlRewrite, $this>
     */
    public function urlRewrites(): MorphMany
    {
        return $this->morphMany(UrlRewrite::class, 'target');
    }
}
