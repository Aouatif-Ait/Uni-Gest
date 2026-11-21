<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enseignant extends Model{
    protected $table = 'Enseignant';

    protected $primaryKey = 'IDENSG';

    public $timestamps = false;

    protected $fillable = [
        'specialite',
        'grade',
        'encadrant_pfe',
        'IDUSER',
    ];

    public function utilisateur(): BelongsTo{
        return $this->belongsTo(
            Utilisateur::class,
            'IDUSER',
            'IDUSER'
        );
    }

    public function cours(): HasMany{
        return $this->hasMany(
            Cours::class,
            'IDENSG',
            'IDENSG'
        );
    }

    public function pfes(): HasMany{
        return $this->hasMany(
            Pfe::class,
            'IDENSG',
            'IDENSG'
        );
    }

    public function ensgcycle(): HasMany{
        return $this->hasMany(
            Pfe::class,
            'IDENSG',
            'IDENSG'
        );
    }

    public function note(): HasMany{
        return $this->hasMany(
            Note::class,
            'IDNOTE',
            'IDNOTE'
        );
    }
}

