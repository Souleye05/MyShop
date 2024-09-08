<?php

namespace App\Models;

use App\Traits\HasPhoneFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, HasPhoneFilter;

    protected $fillable = ['surnom', 'telephone', 'adresse', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dettes()
    {
        return $this->hasMany(Dette::class);
    }
}

