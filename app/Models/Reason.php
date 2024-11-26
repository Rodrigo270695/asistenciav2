<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reason extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }
}
