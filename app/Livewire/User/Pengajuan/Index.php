<?php

namespace App\Livewire\User\Pengajuan;

use App\Models\Pengajuan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads, WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    // Form properties untuk pengajuan baru
    public $tgl_mulai;
    public $tgl_selesai;
    public $surat_pengantar;
    
    // Modal states
    public $showCreateModal = false;
    public $showDetailModal = false;
    public $selectedPengajuan = null;
    
    protected $rules = [
        'tgl_mulai' => 'required|date|after:today',
        'tgl_selesai' => 'required|date|after:tgl_mulai',
        'surat_pengantar' => 'required|file|mimes:pdf|max:2048',
    ];
    
    protected $messages = [
        'tgl_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tgl_mulai.after' => 'Tanggal mulai harus setelah hari ini.',
        'tgl_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        'surat_pengantar.required' => 'Surat pengantar wajib diupload.',
        'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
        'surat_pengantar.max' => 'Ukuran surat pengantar maksimal 2MB.',
    ];
    
    public function render()
    {
        $pengajuans = Pengajuan::with(['user.sekolah'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.user.pengajuan.index', [
            'pengajuans' => $pengajuans
        ]);
    }
    
    public function openCreateModal()
    {
        // Check if user can create new application
        $existingPengajuan = Pengajuan::where('user_id', Auth::id())
        ->whereIn('status', ['menunggu', 'disetujui'])
        ->first();
        
        if ($existingPengajuan) {
            if ($existingPengajuan->status === 'menunggu') {
                session()->flash('error', 'Anda masih memiliki pengajuan yang sedang menunggu persetujuan.');
                return;
            } elseif ($existingPengajuan->status === 'disetujui') {
                session()->flash('error', 'Anda sudah memiliki pengajuan magang yang disetujui. Tidak dapat mengajukan magang lagi.');
                return;
            }
        }
        $this->resetForm();
        $this->showCreateModal = true;
    }
    
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }
    
    public function resetForm()
    {
        $this->tgl_mulai = '';
        $this->tgl_selesai = '';
        $this->surat_pengantar = null;
    }
    
    public function submit()
    {
        $this->validate();
        
        try {
            // // Check if user already has pending application
            // $existingPengajuan = Pengajuan::where('user_id', Auth::id())
            //     ->where('status', 'menunggu')
            //     ->exists();
                
            // if ($existingPengajuan) {
            //     session()->flash('error', 'Anda masih memiliki pengajuan yang sedang menunggu persetujuan.');
            //     return;
            // }
            
            // Double check - optional for extra security
            $existingPengajuan = Pengajuan::where('user_id', Auth::id())
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->exists();
                
            if ($existingPengajuan) {
                $this->closeCreateModal();
                session()->flash('error', 'Tidak dapat mengajukan magang. Status pengajuan sebelumnya masih aktif.');
                return;
            }

            // Upload surat pengantar
            $suratPengantarPath = null;
            if ($this->surat_pengantar) {
                $filename = 'surat_pengantar/' . time() . '_' . str_replace(' ', '_', Auth::user()->nama) . '_surat_pengantar.pdf';
                $suratPengantarPath = $this->surat_pengantar->storeAs('', $filename, 'public');
            }
            
            // Simpan pengajuan
            Pengajuan::create([
                'user_id' => Auth::id(),
                'tgl_mulai' => $this->tgl_mulai,
                'tgl_selesai' => $this->tgl_selesai,
                'surat_pengantar' => $suratPengantarPath,
                'status' => 'menunggu',
            ]);
            
            session()->flash('message', 'Pengajuan magang berhasil dikirim dan sedang menunggu persetujuan.');
            $this->closeCreateModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi.');
        }
    }
    
    public function openDetailModal($id)
    {
        try {
            // Make sure the pengajuan belongs to the authenticated user
            $this->selectedPengajuan = Pengajuan::with(['user.sekolah'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
                
            $this->showDetailModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Data pengajuan tidak ditemukan.');
        }
    }
    
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPengajuan = null;
    }

    public function dismissAlert()
    {
        session()->forget('message');
        session()->forget('error');
    }
    
    // Helper method to check if file exists
    public function fileExists($path)
    {
        return $path && Storage::disk('public')->exists($path);
    }
    
    // Helper method to get file size
    public function getFileSize($path)
    {
        if ($this->fileExists($path)) {
            $size = Storage::disk('public')->size($path);
            return $this->formatBytes($size);
        }
        return null;
    }
    
    // Helper method to format file size
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}