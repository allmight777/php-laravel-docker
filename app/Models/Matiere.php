<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code'];

    /**
     * Relation
     */
    public function classes()
    {
        return $this->belongsToMany(
            Classe::class,
            'classe_matiere_professeur',
            'matiere_id',
            'classe_id'
        )
        ->distinct()
        ->withPivot('coefficient')
        ->withTimestamps();
    }

    /**
     * Relation vers la table ClasseMatiereProfesseur.
     */
    public function classeMatiereProfesseur()
    {
        return $this->hasMany(ClasseMatiereProfesseur::class);
    }
}
