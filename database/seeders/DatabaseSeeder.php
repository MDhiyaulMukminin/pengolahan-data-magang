<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Role;
use App\Models\User;
use App\Models\Pengajuan;
use App\Models\PesertaMagang;
use App\Models\AlumniMagang;
use App\Models\Sekolah;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();
        // ===== Insert Roles =====
        Role::insert([
            ['id' => 1, 'nama' => 'admin'],
            ['id' => 2, 'nama' => 'user'],
        ]);

         // ===== Insert Sekolahs =====
        Sekolah::insert([
        // Universitas
        ['id' => 1, 'nama' => 'Universitas Sriwijaya'],
        ['id' => 2, 'nama' => 'Universitas Muhammadiyah Palembang'],
        ['id' => 3, 'nama' => 'Universitas PGRI Palembang'],
        ['id' => 4, 'nama' => 'UIN Raden Fatah Palembang'],
        ['id' => 5, 'nama' => 'UIN Walisongo Semarang'],
        ['id' => 6, 'nama' => 'UIN Sunan Kalijaga Yogyakarta'],
        ['id' => 7, 'nama' => 'Politeknik Negeri Sriwijaya'],
        ['id' => 8, 'nama' => 'Universitas Sumatera Selatan (USS)'],
        
        // SMK
        ['id' => 9, 'nama' => 'SMK Negeri 1 Palembang'],
        ['id' => 10, 'nama' => 'SMK Negeri 2 Palembang'], 
        ['id' => 11, 'nama' => 'SMK Negeri 3 Palembang'],
        ['id' => 12, 'nama' => 'SMK Negeri 8 Palembang'], 
        ['id' => 13, 'nama' => 'SMK Muhammadiyah 1 Palembang'],
        ['id' => 14, 'nama' => 'SMK PGRI 2 Palembang'],
        ['id' => 15, 'nama' => 'SMK YPI Tunas Bangsa Palembang'],
        ]);

        // ===== Insert Admin User =====
        User::insert([
            [
                'role_id' => 1,
                'sekolah_id' => null,
                'no_induk' => null,
                'nama' => 'Admin',
                'jurusan' => null,
                'email' => 'dev@dev.com',
                'no_whatsapp' => null,
                'status_akun' => 'aktif',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 2,
                'no_induk' => '632021001',
                'nama' => 'Syahnan Romadhon',
                'jurusan' => 'Hukum Keluarga',
                'email' => 'syahnan@gmail.com',
                'no_whatsapp' => '081377819910',
                'status_akun' => 'aktif',
                'password' => Hash::make('syahnan'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 2,
                'no_induk' => '632021002',
                'nama' => 'Dila Utari',
                'jurusan' => 'Hukum Keluarga',
                'email' => 'dilautari@gmail.com',
                'no_whatsapp' => '083176689019',
                'status_akun' => 'aktif',
                'password' => Hash::make('dilautari'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 2,
                'no_induk' => '632021003',
                'nama' => 'Serdo Heldin Nurdiawan',
                'jurusan' => 'Hukum Keluarga',
                'email' => 'serdoheldin@gmail.com',
                'no_whatsapp' => '081267678091',
                'status_akun' => 'aktif',
                'password' => Hash::make('serdoheldin'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 2,
                'no_induk' => '632021004',
                'nama' => 'Zuhrotul Aini',
                'jurusan' => 'Hukum Keluarga',
                'email' => 'zuhrotul@gmail.com',
                'no_whatsapp' => '082156312890',
                'status_akun' => 'aktif',
                'password' => Hash::make('zuhrotul'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 5,
                'no_induk' => '2202016019',
                'nama' => 'Lela Nopela',
                'jurusan' => 'Hukum Keluarga Islam',
                'email' => 'lelanopela@gmail.com',
                'no_whatsapp' => '085778129901',
                'status_akun' => 'aktif',
                'password' => Hash::make('lelanopela'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id' => 2,
                'sekolah_id' => 6,
                'no_induk' => '21103060025',
                'nama' => 'Alfin Fadhli',
                'jurusan' => 'Perbandingan Mazhab',
                'email' => 'alfinfadhli@gmail.com',
                'no_whatsapp' => '085758217569',
                'status_akun' => 'aktif',
                'password' => Hash::make('alfinfadhli'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // // ===== Insert Pengajuan Peserta =====
        Pengajuan::insert([  // Simpan ke variabel $pengajuan
               [
                'user_id' => 2,
                'tgl_mulai' => '2024-08-01',
                'tgl_selesai' => '2024-09-01',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
               [
                'user_id' => 3,
                'tgl_mulai' => '2024-08-01',
                'tgl_selesai' => '2024-09-01',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
               [
                'user_id' => 4,
                'tgl_mulai' => '2024-08-01',
                'tgl_selesai' => '2024-09-01',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
               [
                'user_id' => 5,
                'tgl_mulai' => '2024-08-01',
                'tgl_selesai' => '2024-09-01',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
               [
                'user_id' => 6,
                'tgl_mulai' => '2024-07-01',
                'tgl_selesai' => '2024-07-19',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
               [
                'user_id' => 7,
                'tgl_mulai' => '2024-10-01',
                'tgl_selesai' => '2024-10-31',
                'surat_pengantar' => 'surat_pengajuan.pdf',
                'status' => 'disetujui',
                'surat_balasan' => 'surat_balasan.pdf',
                'created_at' => $now,
                'updated_at' => $now,
               ],
        ]);

        // ===== Insert Peserta Magang (jika sudah disetujui) =====
        PesertaMagang::insert([  // Simpan ke variabel $peserta_magang
            [
            'pengajuan_id' => 1,
            'status' => 'selesai',
            ],
            [
            'pengajuan_id' => 2,
            'status' => 'selesai',
            ],
            [
            'pengajuan_id' => 3,
            'status' => 'selesai',
            ],
            [
            'pengajuan_id' => 4,
            'status' => 'selesai',
            ],
            [
            'pengajuan_id' => 5,
            'status' => 'selesai',
            ],
            [
            'pengajuan_id' => 6,
            'status' => 'selesai',
            ],
        ]);

        // ===== Insert Alumni Magang Data =====
        AlumniMagang::insert([
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Rafita Sari',
                'jurusan' => 'Psikologi Islam',
                'nama_sekolah' => 'UIN Raden Fatah Palembang',
                'tgl_mulai' => '2025-02-04',
                'tgl_selesai' => '2025-03-10',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Salma Dini Zafira',
                'jurusan' => 'Psikologi Islam',
                'nama_sekolah' => 'UIN Raden Fatah Palembang',
                'tgl_mulai' => '2025-02-04',
                'tgl_selesai' => '2025-03-10',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Dian Nairur Rohman',
                'jurusan' => 'Bimbingan Konseling',
                'nama_sekolah' => 'Universitas PGRI Palembang',
                'tgl_mulai' => '2024-10-03',
                'tgl_selesai' => '2024-12-14',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Vebrina Zullianti Saputri',
                'jurusan' => 'Bimbingan Konseling',
                'nama_sekolah' => 'Universitas PGRI Palembang',
                'tgl_mulai' => '2024-10-03',
                'tgl_selesai' => '2024-12-14',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Aanisa Firdah Nabilah',
                'jurusan' => 'Bimbingan Konseling',
                'nama_sekolah' => 'Universitas PGRI Palembang',
                'tgl_mulai' => '2024-10-03',
                'tgl_selesai' => '2024-12-14',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Apriani',
                'jurusan' => 'Bimbingan Konseling',
                'nama_sekolah' => 'Universitas PGRI Palembang',
                'tgl_mulai' => '2024-10-03',
                'tgl_selesai' => '2024-12-14',
                'keterangan' => 'Manual',
            ],
            [
                'peserta_magang_id' => null,
                'nama_alumni' => 'Wulandari',
                'jurusan' => 'Bimbingan Konseling',
                'nama_sekolah' => 'Universitas PGRI Palembang',
                'tgl_mulai' => '2024-10-03',
                'tgl_selesai' => '2024-12-14',
                'keterangan' => 'Manual',
            ],
        ]);
    }
}
