<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
   protected $fillable = ['eleve_id', 'matiere_id', 'type_evaluation', 'nom_evaluation', 'periode', 'valeur'];

}
