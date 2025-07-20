<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniMagang extends Model
{
    protected $fillable = [
        'peserta_magang_id',
        'nama_alumni',
        'jurusan',
        'nama_sekolah',
        'tgl_mulai',
        'tgl_selesai',
        'keterangan',
    ];


    // Relasi ke PesertaMagang (Many-to-One)
    public function pesertaMagang()
    {
        return $this->belongsTo(PesertaMagang::class);
    }

    // Relasi ke User melalui PesertaMagang (HasOneThrough)
    public function user()
    {
        return $this->hasOneThrough(User::class, PesertaMagang::class, 'id', 'id', 'peserta_magang_id', 'user_id');
    }
}
