<?php

namespace App\Models;

use App\Models\Traits\TenantAware;
use App\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends BaseModel
{
    use TenantAware, Translatable;

    protected static function booted()
    {
        static::saving(function (Category $category) {
            if ($category->isDirty('parent_id') && $category->parent_id !== null) {
                // Prevent self-assignment
                if ($category->id === $category->parent_id) {
                    throw new \DomainException("A category cannot be its own parent.");
                }

                // Prevent cycle by checking ancestors
                $currentParentId = $category->parent_id;
                while ($currentParentId !== null) {
                    if ($currentParentId === $category->id) {
                        throw new \DomainException("Category hierarchy cycle detected.");
                    }
                    
                    $parent = Category::find($currentParentId);
                    $currentParentId = $parent ? $parent->parent_id : null;
                }
            }
        });
    }

    /**
     * Override the Eloquent builder to block bulk parent_id mutations.
     * Direct bulk update of parent_id bypasses the saving event (cycle check),
     * so it is explicitly prohibited. parent_id must be changed through model save().
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
                if (array_key_exists('parent_id', $values)) {
                    throw new \DomainException(
                        "Bulk update of parent_id on categories is prohibited. " .
                        "Use model->parent_id = X; model->save() to ensure cycle prevention."
                    );
                }
                return parent::update($values);
            }
        };
    }

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
     * Get the URL rewrites for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<UrlRewrite, $this>
     */
    public function urlRewrites(): MorphMany
    {
        return $this->morphMany(UrlRewrite::class, 'target');
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
