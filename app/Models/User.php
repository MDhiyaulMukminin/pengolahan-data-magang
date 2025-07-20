<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'sekolah_id',
        'no_induk',
        'nama',
        'jurusan',
        'email',
        'no_whatsapp',
        'status_akun',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Role (Many-to-One)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke Sekolah (Many-to-One)
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    // Relasi ke PesertaMagang melalui Pengajuan (HasManyThrough)
    public function pesertaMagang()
    {
        return $this->hasManyThrough(PesertaMagang::class, Pengajuan::class);
    }

    // Relasi ke AlumniMagang melalui PesertaMagang (HasManyThrough)
    public function alumniMagang()
    {
        return $this->hasManyThrough(AlumniMagang::class, PesertaMagang::class);
    }
}
