<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUlids;

    /**
     * Disable auto-incrementing IDs since we are using ULID.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set the primary key type to string.
     *
     * @var string
     */
    protected $keyType = 'string';
}
