<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relasi ke User (One-to-Many)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
