<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model{

    protected $table = 'Note';
    
    protected $primary_key = 'IDNOTE';

    public $timestamps = false;

    public function examen(): HasMany{
        return $this->hasMany(
            Examen::class,
            'IDNOTE',
            'IDNOTE'
        );
    }

    public function enseignant(): BelongsTo{
        return $this -> belongsTo(
            Enseignant :: class,
            'IDENSG',
            'IDENSG'
        );
    }

}
