<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detailhorary extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function horary(): BelongsTo
    {
        return $this->belongsTo(Horary::class);
    }
}
