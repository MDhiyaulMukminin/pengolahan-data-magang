<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;
    
    protected $table = 'pengajuans';
    
    protected $fillable = [
        'user_id',
        'tgl_mulai',
        'tgl_selesai',
        'surat_pengantar',
        'status',
        'surat_balasan',
    ];

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke PesertaMagang (One-to-Many)
    public function pesertaMagang()
    {
        return $this->hasMany(PesertaMagang::class);
    }
}