<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $no_induk, $role_id, $sekolah_id, $nama, $jurusan, $sekolah_nama, $email, $no_whatsapp, $status_akun, $password;
    public $user_id, $isOpen = false, $isDelete = false, $isView = false, $isEdit = false;
    public $search = '', $searchSekolah = '';
    public $filteredSekolah = [];
    public $showDropdown = false;
    public $highlightIndex = 0;
    
    protected $listeners = ['dismissAlert'];
    
    protected function rules()
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $this->user_id,
            'role_id' => 'required|integer',
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
        ];

        // Tambahkan rules untuk edit mode
        if ($this->isEdit) {
            $rules += [
                'sekolah_id' => 'nullable|integer',
                'no_induk' => 'nullable|string|max:50',
                'jurusan' => 'nullable|string|max:100',
                'no_whatsapp' => 'nullable|string|max:20',
                'status_akun' => 'required|in:aktif,nonaktif,belum lengkapi profile',
            ];
        }

        return $rules;
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        
        $users = User::where('nama', 'like', $search)
            ->orWhere('email', 'like', $search)
            ->orWhere('sekolah_id', 'like', $search)
            ->orWhere('jurusan', 'like', $search)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('livewire.admin.user.index', [
            'users' => $users
        ]);
    }
    
    public function dismissAlert()
    {
        session()->forget('message');
    }
    
    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
        $this->isEdit = false; // Pastikan ini adalah mode tambah
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->isDelete = false;
        $this->isView = false;
        $this->isEdit = false;
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->user_id = null;
        $this->role_id = '';
        $this->sekolah_id = '';
        $this->no_induk = '';
        $this->nama = '';
        $this->jurusan = '';
        $this->sekolah_nama = '';
        $this->email = '';
        $this->no_whatsapp = '';
        $this->status_akun = '';
        $this->password = '';
        $this->filteredSekolah = [];
        $this->showDropdown = false;
        $this->highlightIndex = 0;
    }
    
    public function store()
    {
        $this->validate();
        
        $userData = [
            'nama' => $this->nama,
            'email' => $this->email,
            'role_id' => $this->role_id,
        ];

        // Untuk mode tambah, set nilai default
        if (!$this->isEdit) {
            $userData += [
                'sekolah_id' => null,
                'no_induk' => null,
                'jurusan' => null,
                'no_whatsapp' => null,
                'status_akun' => 'belum lengkapi profile',
            ];
        } else {
            // Untuk mode edit, gunakan semua data
            $userData += [
                'sekolah_id' => $this->sekolah_id ?: null,
                'no_induk' => $this->no_induk,
                'jurusan' => $this->jurusan,
                'no_whatsapp' => $this->no_whatsapp,
                'status_akun' => $this->status_akun,
            ];
        }
        
        // Only update password if provided
        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }
        
        if ($this->user_id) {
            User::find($this->user_id)->update($userData);
            session()->flash('message', 'Data pengguna berhasil diperbarui!');
        } else {
            User::create($userData);
            session()->flash('message', 'Data pengguna berhasil ditambahkan!');
        }
        
        $this->closeModal();
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->role_id = $user->role_id;
        $this->sekolah_id = $user->sekolah_id;
        $this->no_induk = $user->no_induk;
        $this->nama = $user->nama;
        $this->jurusan = $user->jurusan;
        $this->sekolah_nama = $user->sekolah->nama ?? '';
        $this->email = $user->email;
        $this->no_whatsapp = $user->no_whatsapp;
        $this->status_akun = $user->status_akun;
        
        $this->isOpen = true;
        $this->isEdit = true; // Set ke mode edit
    }
    
    public function view($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->role_id = $user->role_id;
        $this->sekolah_id = $user->sekolah_id;
        $this->no_induk = $user->no_induk;
        $this->nama = $user->nama;
        $this->jurusan = $user->jurusan;
        $this->sekolah_nama = $user->sekolah->nama ?? '-';
        $this->email = $user->email;
        $this->no_whatsapp = $user->no_whatsapp;
        $this->status_akun = $user->status_akun;
        
        $this->isView = true;
    }
    
    public function deleteConfirm($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->nama = $user->nama; // Store the name for display in confirmation
        $this->isDelete = true;
    }
    
    public function delete()
    {
        User::find($this->user_id)->delete();
        session()->flash('message', 'Data pengguna berhasil dihapus!');
        $this->closeModal();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Method yang diperbaiki untuk menangani perubahan input sekolah
    public function updatedSekolahNama()
    {
        // Reset sekolah_id ketika nama sekolah berubah
        $this->sekolah_id = '';
        
        if (strlen($this->sekolah_nama) >= 2) {
            $this->filteredSekolah = \App\Models\Sekolah::where('nama', 'like', '%' . $this->sekolah_nama . '%')
                ->orderBy('nama')
                ->limit(10)
                ->get();
    
            $this->showDropdown = count($this->filteredSekolah) > 0;
        } else {
            $this->filteredSekolah = [];
            $this->showDropdown = false;
        }
    
        $this->highlightIndex = 0;
    }

    public function selectSekolah($id)
    {
        $sekolah = \App\Models\Sekolah::find($id);

        if ($sekolah) {
            $this->sekolah_id = $sekolah->id;
            $this->sekolah_nama = $sekolah->nama;
            $this->showDropdown = false;
            $this->filteredSekolah = [];
        }
    }

    public function selectNext()
    {
        if (count($this->filteredSekolah) > 0 && $this->highlightIndex < count($this->filteredSekolah) - 1) {
            $this->highlightIndex++;
        }
    }

    public function selectPrevious()
    {
        if ($this->highlightIndex > 0) {
            $this->highlightIndex--;
        }
    }

    public function chooseSekolah()
    {
        if (isset($this->filteredSekolah[$this->highlightIndex])) {
            $this->selectSekolah($this->filteredSekolah[$this->highlightIndex]->id);
        }
    }

    // Method tambahan untuk menangani fokus input
    public function focusSekolah()
    {
        if (strlen($this->sekolah_nama) >= 2 && count($this->filteredSekolah) > 0) {
            $this->showDropdown = true;
        }
    }

    // Method untuk menyembunyikan dropdown
    public function hideDropdown()
    {
        // Delay sedikit untuk memberikan waktu click event pada dropdown item
        $this->showDropdown = false;
    }
}