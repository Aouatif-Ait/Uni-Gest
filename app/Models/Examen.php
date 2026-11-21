<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    protected $table = 'Examen';
    
    protected $primary_key = 'IDEXAMEN';

    public $timestamps = false;

    public function note(): BelongsTo{
        return $this -> belongsTo(
            Note :: class,
            'IDNOTE',
            'IDNOTE'
        );
    }

    public function module(): BelongsTo{
        return $this -> belongsTo(
            Module :: class,
            'IDEXAMEN',
            'IDEXAMEN'
        );
    }
}