<?php

namespace App\Livewire\Admin\Pengajuan;

use App\Models\Pengajuan;
use App\Models\PesertaMagang;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    
    protected $paginationTheme = 'bootstrap';
    
    // Search dan filter properties
    public $search = '';
    public $statusFilter = 'semua';
    
    // Response modal properties
    public $status = '';
    public $surat_balasan;
    
    // Detail modal properties
    public $selectedPengajuan = null;
    
    // State untuk modals
    public $showDetailModal = false;
    public $showResponseModal = false;
    public $pengajuanId = null;
    
    protected $listeners = ['searchUpdated'];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'semua'],
    ];
    
    protected $rules = [
        'status' => 'required|in:disetujui,ditolak',
        'surat_balasan' => 'nullable|file|mimes:pdf|max:2048',
    ];
    
    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->statusFilter = request()->query('statusFilter', $this->statusFilter);
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function searchUpdated($value)
    {
        $this->search = $value;
        $this->resetPage();
    }
    
    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    
    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $query = Pengajuan::with(['user.sekolah'])->whereHas('user', function($query) use ($searchTerm) {
            $query->where('nama', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('jurusan', 'like', $searchTerm)
                ->orWhereHas('sekolah', function ($q) use ($searchTerm) {
                    $q->where('nama', 'like', $searchTerm);
                });
        });
        
        
        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }
        
        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Hitung jumlah pengajuan berdasarkan status
        $counters = [
            'semua' => Pengajuan::count(),
            'menunggu' => Pengajuan::where('status', 'menunggu')->count(),
            'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
        ];
        
        return view('livewire.admin.pengajuan.index', [
            'pengajuans' => $pengajuans,
            'counters' => $counters
        ]);
    }
    
    public function openDetailModal($id)
    {
        $this->selectedPengajuan = Pengajuan::with('user')->findOrFail($id);
        $this->showDetailModal = true;
    }
    
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPengajuan = null;
    }
    
    public function openResponseModal($id)
    {
        $this->pengajuanId = $id;
        $this->selectedPengajuan = Pengajuan::with('user')->findOrFail($id);
        
        // Pre-fill dengan data yang ada jika sudah ada respons
        $this->status = $this->selectedPengajuan->status !== 'menunggu' ? $this->selectedPengajuan->status : '';
        
        $this->showResponseModal = true;
    }
    
    public function closeResponseModal()
    {
        $this->showResponseModal = false;
        $this->resetResponseForm();
    }
    
    public function resetResponseForm()
    {
        $this->pengajuanId = null;
        $this->selectedPengajuan = null;
        $this->status = '';
        $this->surat_balasan = null;
    }
    
    public function submitResponse()
    {
        $this->validate();
        
        $pengajuan = Pengajuan::findOrFail($this->pengajuanId);
        
        // Update status dan catatan
        $pengajuan->status = $this->status;
        
        // Upload surat balasan jika ada
        if ($this->surat_balasan) {
            // Hapus file lama jika ada
            if ($pengajuan->surat_balasan) {
                Storage::disk('public')->delete($pengajuan->surat_balasan);
            }
            
            // Simpan file baru
            $filename = 'surat_balasan/'. time() . '_' . $pengajuan->user->nama . '.pdf';
            $path = $this->surat_balasan->storeAs('', $filename, 'public');
            $pengajuan->surat_balasan = $path;
        }
        
        $pengajuan->save();

        if ($pengajuan->status === 'disetujui') {
            $sudahAda = PesertaMagang::where('pengajuan_id', $pengajuan->id)->exists();

            if (!$sudahAda) {
                
                PesertaMagang::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status' => now()->gte(Carbon::parse($pengajuan->tgl_mulai)) ? 'aktif' : 'menunggu',
                ]);
            }
        }        
        
        session()->flash('message', 'Pengajuan berhasil direspon.');
        $this->closeResponseModal();
    }
    
    public function dismissAlert()
    {
        session()->forget('message');
    }
}