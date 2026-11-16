<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filiere extends Model
{
    protected $table = 'Filiere';

    protected $primaryKey = 'IDFILIERE';

    public $timestamps = false;

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(
            Cycle::class,
            'IDCYCLE',
            'IDCYCLE'
        );
    }
}