<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'niveau', 'serie'];

    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'professeur_classe');
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'classe_matiere')
                    ->withPivot('coefficient')
                    ->withTimestamps();
    }
}
