<?php

namespace App\Models;

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
}
