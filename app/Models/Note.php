<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
   protected $fillable = ['bulletin_id','eleve_id', 'matiere_id', 'type_evaluation', 'nom_evaluation', 'periode', 'valeur'];
   
   public function bulletin()
    {
        return $this->belongsTo(Bulletin::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
