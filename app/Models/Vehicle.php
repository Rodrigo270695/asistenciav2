<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function brandmodel(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class);
    }
    public function vehiclecolor(): BelongsTo
    {
        return $this->belongsTo(VehicleColor::class);
    }
    public function vehicletype(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

}
