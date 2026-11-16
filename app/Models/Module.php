<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Module extends Model{
    protected $tabl = 'Module';
    protected $primaryKey = 'IDMODULE';
    public $timestamps = false;

    protected $fillable = [
        'code_mdl',
        'intitule',
        'volume_horaire',
        'IDSEMESTRE',
        'IDEXAMEN',
    ];
    public function semestre() : BelongsTo {
        return $this-> belongsTo(
            Semestre :: class,
            'IDSEMESTRE',
            'IDSEMESTRE'
        );
    }

    public function examen() : BelongsTo {
        return $this-> belongsTo(
            Examen :: class,
            'IDEXAMEN',
            'IDEXAMEN'
        );
    }
}
