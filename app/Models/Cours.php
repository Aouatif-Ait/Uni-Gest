<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cours extends Model
{
    protected $table = 'Cours';

    protected $primaryKey = 'IDCOURS';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'type_cours',
        'chemin_fichier',
        'date_pub',
        'IDMODULE',
        'IDENSG',
    ];

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(
            Enseignant::class,
            'IDENSG',
            'IDENSG'
        );
    }
}
