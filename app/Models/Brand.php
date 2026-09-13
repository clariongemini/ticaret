<?php

namespace App\Models;

use App\Models\Traits\TenantAware;
use App\Models\Traits\Translatable;

class Brand extends BaseModel
{
    use TenantAware, Translatable;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];
}
