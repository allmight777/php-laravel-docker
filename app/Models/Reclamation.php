<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    //
     protected $fillable = [
        'eleve_id', 'professeur_id', 'admin_id', 'message', 
        'statut', 'matiere_id', 'note_id'
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}