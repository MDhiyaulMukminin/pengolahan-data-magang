<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Sekolah extends Model
{
    //
    protected $table = 'sekolahs';

    protected $fillable = [
        'nama',
        'status',
    ];

     // Event yang dijalankan setelah model diupdate
     protected static function booted()
     {
         static::updated(function ($sekolah) {
             // Jika status berubah menjadi 'aktif'
             if ($sekolah->isDirty('status') && $sekolah->status === 'aktif') {
                 // Update semua user yang sekolahnya baru diaktifkan
                 User::where('sekolah_id', $sekolah->id)
                     ->where('status_akun', 'belum lengkapi profile')
                     ->update(['status_akun' => 'aktif']);
             }
             
            //  // Jika status berubah menjadi 'menunggu' (sekolah di-suspend)
            //  if ($sekolah->isDirty('status') && $sekolah->status === 'menunggu') {
            //      // Update semua user yang sekolahnya di-suspend
            //      User::where('sekolah_id', $sekolah->id)
            //          ->where('status_akun', 'aktif')
            //          ->update(['status_akun' => 'belum lengkapi profile']);
            //  }
         });
     }

    // Relasi ke User (One-to-Many)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
