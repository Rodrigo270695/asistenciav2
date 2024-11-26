<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zonal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pdvs(): HasMany
    {
        return $this->hasMany(Pdv::class);
    }
}
