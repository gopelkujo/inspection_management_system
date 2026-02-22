<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    protected $fillable = [
        'inspection_id',
        'lot_no',
        'allocation',
        'owner',
        'condition',
        'qty_available',
        'qty_required',
    ];

    protected $casts = [
        'qty_available' => 'integer',
        'qty_required' => 'integer',
    ];

    public function inspection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
