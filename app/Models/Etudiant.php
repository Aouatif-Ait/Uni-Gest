<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model{
    protected $table = 'Etudiant';

    protected $primaryKey = 'IDETUD';

    public $timestamps = false;

    protected $fillable = [
        'cne',
        'date_naiss',
        'da',
        'IDUSER',
        'IDPFE',
    ];

    public function utilisateur(): BelongsTo{
        return $this->belongsTo(
            Utilisateur::class,
            'IDUSER',
            'IDUSER'
        );
    }

    public function pfe(): BelongsTo{
        return $this->belongsTo(
            Pfe::class,
            'IDPFE',
            'IDPFE'
        );
    }

    public function inscriptions(): HasMany{
        return $this->hasMany(
            Inscription::class,
            'IDETUD',
            'IDETUD'
        );
    }
}