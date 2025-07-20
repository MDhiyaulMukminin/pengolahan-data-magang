<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaMagang extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'status',
        'sertifikat',
    ];


    // Relasi ke Pengajuan (Many-to-One)
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    // // Relasi ke User melalui Pengajuan (HasOneThrough)
    // public function user()
    // {
    //     return $this->hasOneThrough(User::class, Pengajuan::class, 'id', 'id', 'pengajuan_id', 'user_id');
    // }

    public function user()
    {
        return $this->pengajuan->user ?? null;
    }

    // Relasi ke AlumniMagang (One-to-Many)
    public function alumniMagang()
    {
        return $this->hasMany(AlumniMagang::class);
    }
}
