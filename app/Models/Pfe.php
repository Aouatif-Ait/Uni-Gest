<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pfe extends Model
{
    protected $table = 'Pfe';

    protected $primaryKey = 'IDPFE';

    public $timestamps = false;

    protected $fillable = [
        'date_affec',
        'IDSUJET',
        'IDENSG',
        'IDNOTE',
    ];

    public function enseignant(): BelongsTo{
        return $this->belongsTo(
            Enseignant::class,
            'IDENSG',
            'IDENSG'
        );
    }

    public function etudiants(): HasMany{
        return $this->hasMany(
            Etudiant::class,
            'IDPFE',
            'IDPFE'
        );
    }

    public function sujet(): BelongsTo{
        return $this->belongsTo(
            Sujetpfe::class,
            'IDSUJET',
            'IDSUJET'
        );
    }
}