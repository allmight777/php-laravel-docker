<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code'];

    /**
     * Relation
     */
    public function matieres()
    {
        return $this->belongsToMany(
            Matiere::class,
            'classe_matiere_professeur',
            'classe_id',
            'matiere_id'
        )
        ->withPivot('professeur_id', 'coefficient')
        ->withTimestamps()
        ->distinct();
    }

    
}
