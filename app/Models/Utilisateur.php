<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['nom', 'prenom', 'email', 'mtpasse'])]
#[Hidden(['mtpasse'])]

class Utilisateur extends Authenticatable{
    protected $table = 'Utilisateur';

    protected $primaryKey = 'IDUSER';

    public $timestamps = false;

    public function getAuthPasswordName(){
        return 'mtpasse';
    }

    public function getAuthPassword(){
        return $this->mtpasse;
    }

    protected function casts(): array{
        return [
            'mtpasse' => 'hashed',
        ];
    }
    public function enseignant(): HasOne{
        return $this->hasOne(
            Enseignant::class,
            'IDUSER',
            'IDUSER'
        );
    }


}
