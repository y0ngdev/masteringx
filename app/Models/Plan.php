<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Plan extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'features' => 'array',
        'gateway_meta' => 'array',
        'price' => 'string'
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Number::currency($value, 'USD', locale: 'en_US',precision: 0) : '$0'
        );
    }

}
