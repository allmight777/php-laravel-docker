<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'telephone', 'photo', 'email', 'mot_de_passe'];

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'professeur_classe');
    }
}
