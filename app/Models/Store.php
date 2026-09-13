<?php

namespace App\Models;

use App\Models\Traits\TenantAware;

class Store extends BaseModel
{
    use TenantAware;
    
    protected $guarded = [];
}
