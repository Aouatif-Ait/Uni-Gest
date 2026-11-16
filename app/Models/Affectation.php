<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Affectation extends Model{
    protected $table = 'Affectation';
    // Clé primaire composée : IDENSG, IDMODULE, IDANNEE
    // Ce modèle est utilisé ici pour consulter les relations
    public $timestamps = false;

    public function enseignant(): BelongsTo{
        return $this->belongsTo(
            Enseignant::class,
            'IDENSG',
            'IDENSG'
        );
    }

    public function module(): BelongsTo{
        return $this->belongsTo(
            Module::class,
            'IDMODULE',
            'IDMODULE'
        );
    }

    public function annee(): BelongsTo{
        return $this->belongsTo(
            Anneeuniv::class,
            'IDANNEE',
            'IDANNEE'
        );
    }
}
