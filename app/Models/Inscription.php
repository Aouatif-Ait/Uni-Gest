<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model{
    protected $table = 'Inscription';

    // Clé primaire composée : IDETUD, IDFILIERE, IDANNEE
    // Ce modèle est utilisé ici pour consulter les relations.

    public $timestamps = false;

    public function etudiant(): BelongsTo{
        return $this->belongsTo(
            Etudiant::class,
            'IDETUD',
            'IDETUD'
        );
    }

    public function filiere(): BelongsTo{
        return $this->belongsTo(
            Filiere::class,
            'IDFILIERE',
            'IDFILIERE'
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