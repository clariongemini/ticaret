<?php

namespace App\Models;

use App\Models\Traits\TenantAware;
use App\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel
{
    use TenantAware, Translatable;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Get the parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Scope a query to only include root categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // CTE methods for descendants/ancestors would be added via a specialized repository or trait
    // since recursive CTEs in Eloquent typically require raw queries or specific packages (like staudenmeir/laravel-cte).
}
