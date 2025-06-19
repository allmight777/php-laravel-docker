<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = [
        'eleve_id',
        'periode_id',
        'total',
        'moyenne',
        'moyenne_coefficient',
        'moyenne_generale',
        'moyenne_periodique',
        'rang_periodique',
        'statut',
        'matiere_id',
        'rang',
    ];

    // Relations 
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeAcademique::class);
    }
}
