<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Index extends Component
{
    public $nama;
    public $sekolah_id;
    public $no_whatsapp;
    public $no_induk;
    public $jurusan;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    // New properties for add school feature
    public $nama_sekolah_baru;
    public $isAddingSchool = false;
    
    public $isEditingProfile = false;
    public $isEditingPassword = false;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'sekolah_id' => 'required|exists:sekolahs,id',
        'no_whatsapp' => 'required|string|max:20',
        'no_induk' => 'required|string|max:50',
        'jurusan' => 'required|string|max:100',
        'current_password' => 'required_with:new_password',
        'new_password' => 'nullable|min:8|confirmed',
        'nama_sekolah_baru' => 'required_if:isAddingSchool,true|string|max:255',
    ];

    protected $messages = [
        'nama.required' => 'Nama wajib diisi',
        'sekolah_id.required' => 'Sekolah wajib dipilih',
        'sekolah_id.exists' => 'Sekolah yang dipilih tidak valid',
        'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi',
        'no_induk.required' => 'Nomor Induk wajib diisi',
        'no_induk.unique' => 'Nomor Induk sudah digunakan oleh pengguna lain',
        'jurusan.required' => 'Jurusan wajib diisi',
        'current_password.required_with' => 'Password saat ini wajib diisi untuk mengubah password',
        'new_password.min' => 'Password baru minimal 8 karakter',
        'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
        'nama_sekolah_baru.required_if' => 'Nama sekolah wajib diisi',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->nama = $user->nama;
        $this->sekolah_id = $user->sekolah_id;
        $this->no_whatsapp = $user->no_whatsapp;
        $this->no_induk = $user->no_induk;
        $this->jurusan = $user->jurusan;
        $this->email = $user->email;
    }

    public function toggleEditProfile()
    {
        $this->isEditingProfile = !$this->isEditingProfile;
        if (!$this->isEditingProfile) {
            $this->mount(); // Reset data jika cancel
            $this->resetAddSchool(); // Reset form add school
            $this->resetErrorBag(); // Reset error messages
        }
    }

    public function toggleEditPassword()
    {
        $this->isEditingPassword = !$this->isEditingPassword;
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function toggleAddSchool()
    {
        $this->isAddingSchool = !$this->isAddingSchool;
        if (!$this->isAddingSchool) {
            $this->resetAddSchool();
        }
    }

    public function resetAddSchool()
    {
        $this->nama_sekolah_baru = '';
        $this->isAddingSchool = false;
    }

    public function addNewSchool()
    {
        $this->validate([
            'nama_sekolah_baru' => 'required|string|max:255',
        ]);

        try {
            // Cek apakah sekolah dengan nama yang sama sudah ada
            $existingSchool = Sekolah::where('nama', $this->nama_sekolah_baru)->first();
            
            if ($existingSchool) {
                $this->addError('nama_sekolah_baru', 'Sekolah dengan nama tersebut sudah ada dalam database.');
                return;
            }

            // Buat sekolah baru dengan status "menunggu" untuk diverifikasi admin
            $newSchool = Sekolah::create([
                'nama' => $this->nama_sekolah_baru,
                'status' => 'menunggu', // Status menunggu verifikasi admin
            ]);

            // Set sekolah yang baru dibuat sebagai pilihan
            $this->sekolah_id = $newSchool->id;
            
            // Reset form add school
            $this->resetAddSchool();
            
            session()->flash('success', 'Sekolah baru berhasil ditambahkan dan akan diverifikasi oleh admin. Anda dapat melanjutkan mengisi profil.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menambahkan sekolah: ' . $e->getMessage());
        }
    }

    public function updateProfile()
    {
        // Membuat rules dinamis untuk no_induk dengan unique validation
        $userId = Auth::id();
        $rules = [
            'nama' => 'required|string|max:255',
            'sekolah_id' => 'required|exists:sekolahs,id',
            'no_whatsapp' => 'required|string|max:20',
            'no_induk' => "required|string|max:50|unique:users,no_induk,{$userId}",
            'jurusan' => 'required|string|max:100',
        ];

        $this->validate($rules);

        try {
            $user = User::find(Auth::id());
            $sekolah = Sekolah::find($this->sekolah_id);
            
            // Cek apakah nomor induk sudah digunakan oleh user lain
            $existingUser = User::where('no_induk', $this->no_induk)
                                ->where('id', '!=', $userId)
                                ->first();
            
            if ($existingUser) {
                $this->addError('no_induk', 'Nomor Induk sudah digunakan oleh pengguna lain.');
                return;
            }
            
            $user->nama = $this->nama;
            $user->sekolah_id = $this->sekolah_id;
            $user->no_whatsapp = $this->no_whatsapp;
            $user->no_induk = $this->no_induk;
            $user->jurusan = $this->jurusan;
            
            // Logic untuk status akun berdasarkan status sekolah
            if ($user->status_akun === 'belum lengkapi profile') {
                if ($sekolah && $sekolah->status === 'aktif') {
                    // Sekolah sudah diverifikasi admin, akun langsung aktif
                    $user->status_akun = 'aktif';
                }
            }
            
            $user->save();

            $this->isEditingProfile = false;
            
            // Pesan berbeda berdasarkan status sekolah
            if ($user->status_akun === 'aktif' && $user->wasChanged('status_akun')) {
                session()->flash('success', 'Selamat! Profil Anda sudah lengkap dan akun telah aktif.');
            } elseif ($sekolah && $sekolah->status !== 'aktif') {
                session()->flash('warning', 'Profil berhasil disimpan. Akun Anda sedang menunggu verifikasi sekolah oleh admin.');
            } else {
                session()->flash('success', 'Profil berhasil diperbarui!');
            } 
          
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation
                if (strpos($e->getMessage(), 'no_induk') !== false) {
                    $this->addError('no_induk', 'Nomor Induk sudah digunakan oleh pengguna lain.');
                } else {
                    session()->flash('error', 'Terjadi kesalahan validasi data. Silakan periksa data yang dimasukkan.');
                }
            } else {
                session()->flash('error', 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        try {
            $user = User::find(Auth::id());
            $user->password = Hash::make($this->new_password);
            $user->save();

            $this->isEditingPassword = false;
            $this->current_password = '';
            $this->new_password = '';
            $this->new_password_confirmation = '';
            
            session()->flash('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage());
        }
    }

    // Method untuk validasi real-time nomor induk (opsional)
    public function updatedNoInduk()
    {
        $userId = Auth::id();
        $existingUser = User::where('no_induk', $this->no_induk)
                            ->where('id', '!=', $userId)
                            ->first();
        
        if ($existingUser && !empty($this->no_induk)) {
            $this->addError('no_induk', 'Nomor Induk sudah digunakan oleh pengguna lain.');
        } else {
            $this->resetErrorBag('no_induk');
        }
    }

    // Method untuk cek status sekolah user
    public function getSchoolStatus()
    {
        if ($this->sekolah_id) {
            $sekolah = Sekolah::find($this->sekolah_id);
            return $sekolah ? $sekolah->status : null;
        }
        return null;
    }

    public function render()
    {
        // Hanya tampilkan sekolah yang sudah aktif untuk dropdown utama
        $sekolahs = Sekolah::where('status', 'aktif')->orderBy('nama')->get();
        
        // Jika user sudah memiliki sekolah yang statusnya menunggu, tetap tampilkan
        $userSekolah = null;
        if ($this->sekolah_id) {
            $userSekolah = Sekolah::find($this->sekolah_id);
        }

        // Selalu ambil data user terbaru dari database
        $currentUser = User::find(Auth::id());
        
        return view('livewire.user.profile.index', [
            'sekolahs' => $sekolahs,
            'userSekolah' => $userSekolah,
            'user' => $currentUser // Data user terbaru
        ]);
    }
}