<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eleve extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'classe_id',
        'annee_academique_id',

    ];

    protected $hidden = ['password', 'remember_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    //Relation avec les bulletins
    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }

    public function anneesScolaires()
    {
        return $this->bulletins()
            ->select('annee_academique_id')
            ->distinct()
            ->orderBy('annee_academique_id', 'desc')
            ->pluck('annee_academique_id');
    }
}
