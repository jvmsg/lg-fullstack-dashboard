<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMetric extends Model
{
    protected $fillable = [
        'product_type_id',
        'day',
        'quantity_produced',
        'quantity_defective',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function getEfficiencyAttribute()
    {
        if ($this->quantity_produced <= 0) {
            return 0;
        }

        return (($this->quantity_produced - $this->quantity_defective) / $this->quantity_produced) * 100;
    }
}
