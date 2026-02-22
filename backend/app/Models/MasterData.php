<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    protected $fillable = [
        'type',
        'code',
        'label',
        'value',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'value' => 'string',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
