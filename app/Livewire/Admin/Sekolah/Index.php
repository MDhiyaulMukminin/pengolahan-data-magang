<?php

namespace App\Livewire\Admin\Sekolah;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithPagination;

    // Properties untuk form
    public $nama, $status, $sekolah_id;
    
    // Properties untuk modal state
    public $isOpen = false;
    public $isView = false;
    public $isDelete = false;
    
    // Property untuk search
    public $search = '';
    
    // Bootstrap pagination theme
    protected $paginationTheme = 'bootstrap';

    // Validation rules
    protected $rules = [
        'nama' => 'required|string|max:255',
        'status' => 'required|in:aktif,menunggu',
    ];

    // Custom validation messages
    protected $messages = [
        'nama.required' => 'Nama sekolah wajib diisi.',
        'nama.string' => 'Nama sekolah harus berupa text.',
        'nama.max' => 'Nama sekolah maksimal 255 karakter.',
        'status.required' => 'Status wajib dipilih.',
        'status.in' => 'Status harus aktif atau menunggu.',
    ];

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Render the component
    public function render()
    {
        $sekolahs = Sekolah::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.sekolah.index', compact('sekolahs'));
    }

    // Open modal for create
    public function openModal()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    // Close all modals
    public function closeModal()
    {
        $this->isOpen = false;
        $this->isView = false;
        $this->isDelete = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Reset form fields
    private function resetForm()
    {
        $this->nama = '';
        $this->status = '';
        $this->sekolah_id = null;
    }

    // Store or update sekolah
    public function store()
    {
        $this->validate();

        try {
            if ($this->sekolah_id) {
                // Update existing sekolah
                $sekolah = Sekolah::findOrFail($this->sekolah_id);
                $sekolah->update([
                    'nama' => $this->nama,
                    'status' => $this->status,
                ]);
                
                Session::flash('message', 'Data sekolah berhasil diperbarui.');
            } else {
                // Create new sekolah
                Sekolah::create([
                    'nama' => $this->nama,
                    'status' => $this->status,
                ]);
                
                Session::flash('message', 'Data sekolah berhasil ditambahkan.');
            }
            $this->closeModal();
            
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    // Edit sekolah
    public function edit($id)
    {
        try {
            $sekolah = Sekolah::findOrFail($id);
            
            $this->sekolah_id = $sekolah->id;
            $this->nama = $sekolah->nama;
            $this->status = $sekolah->status;
            
            $this->isOpen = true;
            
        } catch (\Exception $e) {
            Session::flash('error', 'Data sekolah tidak ditemukan.');
        }
    }

    // Confirm delete
    public function deleteConfirm($id)
    {
        try {
            $sekolah = Sekolah::findOrFail($id);
            $this->sekolah_id = $sekolah->id;
            $this->isDelete = true;
            
        } catch (\Exception $e) {
            Session::flash('error', 'Data sekolah tidak ditemukan.');
        }
    }

    // Delete sekolah
    public function delete()
    {
        try {
            $sekolah = Sekolah::findOrFail($this->sekolah_id);
            $sekolah->delete();
            
            Session::flash('message', 'Data sekolah berhasil dihapus.');
            $this->closeModal();
            
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan saat menghapus data.');
            $this->closeModal();
        }
    }

    // Dismiss alert message
    public function dismissAlert()
    {
        Session::forget('message');
        Session::forget('error');
    }

    // Real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function verifikasi($id)
    {
        try {
            $sekolah = Sekolah::findOrFail($id);
            $sekolah->status = 'aktif';
            $sekolah->save();

            // Aktivasi user yang sudah lengkap profilnya dan sekolahnya ini
            $users = \App\Models\User::where('sekolah_id', $id)
                ->where('status_akun', 'belum lengkapi profile')
                ->get();

            foreach ($users as $user) {
                if ($user->nama && $user->no_induk && $user->no_whatsapp && $user->jurusan) {
                    $user->status_akun = 'aktif';
                    $user->save();
                }
            }

            Session::flash('message', 'Sekolah berhasil diverifikasi dan akun user yang profilnya lengkap telah diaktifkan.');
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan saat memverifikasi sekolah.');
        }
    }

}