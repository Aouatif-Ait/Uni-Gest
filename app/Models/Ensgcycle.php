<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Ensgcycle extends Model{

    protected $table = 'Ensgcycle';
    // Clé primaire composée : IDENSG, IDCYCLE
    // Ce modèle est utilisé ici pour consulter les relations
    public $timestamps = false;
    
    public function enseignant(): BelongsTo{
        return $this->belongsTo(
            Enseignant::class,
            'IDENSG',
            'IDENSG'
        );
    }
    public function cycle(): BelongsTo{
        return $this->belongsTo(
            Cycle::class,
            'IDCYCLE',
            'IDCYCLE'
        );
    }
}