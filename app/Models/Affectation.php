<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
        protected $fillable = [
        'classe_id',
        'matiere_id',
        'professeur_id',
        'annee_academique_id',
    ];
}
