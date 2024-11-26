<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pdv extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function zonal(): BelongsTo
    {
        return $this->belongsTo(Zonal::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function horary(): BelongsTo
    {
        return $this->belongsTo(Horary::class);
    }
}
