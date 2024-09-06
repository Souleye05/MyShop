<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'prix', 'qteStock'];

    public function scopeByLibelle($query, $libelle)
    {
        return $query->where('libelle', $libelle);
    }

    public function dettes()
    {
        return $this->belongsToMany(Dette::class, 'article_dette')->withPivot('qteVente', 'prixVente');
    }
}

