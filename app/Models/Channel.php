<?php

namespace App\Models;

use App\Models\Traits\TenantAware;

class Channel extends BaseModel
{
    use TenantAware;
    
    protected $guarded = [];
}
