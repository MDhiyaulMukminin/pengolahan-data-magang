<?php

namespace App\Livewire\Admin\AlumniMagang;

use App\Models\AlumniMagang;
use App\Models\PesertaMagang;
use App\Models\Sekolah;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    protected $listeners = ['searchUpdated'];
    protected $queryString = ['search' => ['except' => '']];

    public $alumni_id;
    public $peserta_magang_id = null;
    public $nama_alumni = '';
    public $jurusan = '';
    public $nama_sekolah = '';
    public $sekolah_id = null;
    public $tgl_mulai = '';
    public $tgl_selesai = '';
    public $keterangan = '';

    public $isOpen = false;
    public $isEdit = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    public $pesertaMagangList = [];
    public $manualInput = false;
    
    // Properti untuk searchable dropdown sekolah (mengikuti pola User Management)
    public $sekolah_nama = '';
    public $showSekolahDropdown = false;
    public $filteredSekolah = [];
    public $highlightIndex = -1;

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        // PERUBAHAN: Filter hanya peserta magang dengan status "selesai"
        $this->pesertaMagangList = PesertaMagang::with('pengajuan.user')
            ->where('status', 'selesai')
            ->get();
        $this->filteredSekolah = collect();
    }

        // Method untuk memuat peserta yang available (belum menjadi alumni)
        private function loadAvailablePeserta()
        {
            // Dapatkan ID peserta yang sudah menjadi alumni
            $existingAlumniPesertaIds = AlumniMagang::whereNotNull('peserta_magang_id')
                ->pluck('peserta_magang_id')
                ->toArray();
    
            // Filter peserta magang dengan status "selesai" yang belum menjadi alumni
            // Atau jika sedang edit, termasuk peserta yang sedang diedit
            $query = PesertaMagang::with('pengajuan.user')
                ->where('status', 'selesai');
    
            if (!empty($existingAlumniPesertaIds)) {
                if ($this->isEdit && $this->peserta_magang_id) {
                    // Jika sedang edit, exclude semua alumni kecuali yang sedang diedit
                    $query->where(function($q) use ($existingAlumniPesertaIds) {
                        $q->whereNotIn('id', $existingAlumniPesertaIds)
                          ->orWhere('id', $this->peserta_magang_id);
                    });
                } else {
                    // Jika sedang tambah, exclude semua yang sudah menjadi alumni
                    $query->whereNotIn('id', $existingAlumniPesertaIds);
                }
            }
    
            $this->pesertaMagangList = $query->get();
        }

    // Method untuk menangani perubahan input sekolah
    public function updatedSekolahNama()
    {
        $this->highlightIndex = -1;
        
        if (strlen($this->sekolah_nama) >= 2) {
            $this->filteredSekolah = Sekolah::where('nama', 'like', '%' . $this->sekolah_nama . '%')
                ->orderBy('nama')
                ->limit(10)
                ->get();
            $this->showSekolahDropdown = true;
        } else {
            $this->filteredSekolah = collect();
            $this->showSekolahDropdown = false;
        }
        
        // Reset sekolah_id jika input berubah dan tidak cocok dengan yang dipilih
        if ($this->sekolah_id) {
            $sekolah = Sekolah::find($this->sekolah_id);
            if (!$sekolah || $sekolah->nama !== $this->sekolah_nama) {
                $this->sekolah_id = null;
            }
        }
    }

    // Method untuk memilih sekolah dari dropdown
    public function selectSekolah($sekolahId)
    {
        $sekolah = Sekolah::find($sekolahId);
        if ($sekolah) {
            $this->sekolah_id = $sekolahId;
            $this->sekolah_nama = $sekolah->nama;
            $this->nama_sekolah = $sekolah->nama; // Untuk kompatibilitas
            $this->showSekolahDropdown = false;
            $this->highlightIndex = -1;
        }
    }

    // Method untuk navigasi keyboard
    public function selectNext()
    {
        if ($this->showSekolahDropdown && count($this->filteredSekolah) > 0) {
            $this->highlightIndex = min($this->highlightIndex + 1, count($this->filteredSekolah) - 1);
        }
    }

    public function selectPrevious()
    {
        if ($this->showSekolahDropdown && count($this->filteredSekolah) > 0) {
            $this->highlightIndex = max($this->highlightIndex - 1, 0);
        }
    }

    public function chooseSekolah()
    {
        if ($this->showSekolahDropdown && $this->highlightIndex >= 0 && $this->highlightIndex < count($this->filteredSekolah)) {
            $sekolah = $this->filteredSekolah[$this->highlightIndex];
            $this->selectSekolah($sekolah->id);
        }
    }

    public function hideDropdown()
    {
        $this->showSekolahDropdown = false;
        $this->highlightIndex = -1;
    }

    public function focusSekolah()
    {
        if (strlen($this->sekolah_nama) >= 2) {
            $this->showSekolahDropdown = true;
        }
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

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        $alumniMagang = AlumniMagang::where(function ($query) use ($searchTerm) {
                $query->where('nama_alumni', 'like', $searchTerm)
                      ->orWhere('jurusan', 'like', $searchTerm)
                      ->orWhere('nama_sekolah', 'like', $searchTerm)
                      ->orWhere('keterangan', 'like', $searchTerm);
            })
            ->orWhereHas('pesertaMagang.pengajuan.user', function ($query) use ($searchTerm) {
                $query->where('nama', 'like', $searchTerm);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.alumni-magang.index', [
            'alumniMagang' => $alumniMagang
        ]);
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->isEdit = false;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->showSekolahDropdown = false;
    }

    private function resetInputFields()
    {
        $this->alumni_id = null;
        $this->peserta_magang_id = null;
        $this->nama_alumni = '';
        $this->jurusan = '';
        $this->nama_sekolah = '';
        $this->sekolah_id = null;
        $this->sekolah_nama = '';
        $this->tgl_mulai = '';
        $this->tgl_selesai = '';
        $this->keterangan = '';
        $this->manualInput = false;
        $this->showSekolahDropdown = false;
        $this->filteredSekolah = collect();
        $this->highlightIndex = -1;
        $this->loadAvailablePeserta(); // Reload available peserta
    }

    public function enableManualInput()
    {
        $this->manualInput = true;
        $this->peserta_magang_id = null;
        $this->keterangan = 'Manual';
        $this->sekolah_nama = $this->nama_sekolah;
    }

    public function disableManualInput()
    {
        $this->manualInput = false;
        $this->nama_alumni = '';
        $this->jurusan = '';
        $this->nama_sekolah = '';
        $this->sekolah_id = null;
        $this->sekolah_nama = '';
        $this->showSekolahDropdown = false;
        $this->filteredSekolah = collect();
        $this->highlightIndex = -1;
        $this->keterangan = 'Peserta Terdaftar';
    }

    public function selectPeserta()
    {
        if ($this->peserta_magang_id) {
            // PERUBAHAN: Tambahkan validasi status "selesai" saat memilih peserta
            $peserta = PesertaMagang::with('pengajuan.user.sekolah')
                ->where('id', $this->peserta_magang_id)
                ->where('status', 'selesai')
                ->first();
                
            if ($peserta && $peserta->pengajuan && $peserta->pengajuan->user) {
                $user = $peserta->pengajuan->user;
                $this->nama_alumni = $user->nama;
                $this->jurusan = $user->jurusan;
                $this->nama_sekolah = $user->sekolah->nama ?? '';
                $this->sekolah_id = $user->sekolah->id ?? null;
                $this->tgl_mulai = $peserta->pengajuan->tgl_mulai;
                $this->tgl_selesai = $peserta->pengajuan->tgl_selesai;
                $this->keterangan = 'Peserta Terdaftar';
            }
        }
    }
    
    public function store()
    {
        $this->resetValidation();
        $rules = [
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'keterangan' => 'required',
        ];

        if ($this->manualInput) {
            $rules['nama_alumni'] = 'required';
            $rules['jurusan'] = 'required';
            $rules['sekolah_nama'] = 'required';
        } else {
            // PERUBAHAN: Tambahkan validasi custom untuk memastikan peserta memiliki status "selesai"
            $rules['peserta_magang_id'] = [
                'required',
                'exists:peserta_magangs,id',
                function ($attribute, $value, $fail) {
                    // Cek status selesai
                    $peserta = PesertaMagang::find($value);
                    if (!$peserta || $peserta->status !== 'selesai') {
                        $fail('Peserta magang yang dipilih harus memiliki status selesai.');
                        return;
                    }
                    
                    // Cek duplikasi alumni
                    $existingAlumni = AlumniMagang::where('peserta_magang_id', $value);
                    if ($this->alumni_id) {
                        $existingAlumni->where('id', '!=', $this->alumni_id);
                    }
                    
                    if ($existingAlumni->exists()) {
                        $fail('Peserta ini sudah terdaftar sebagai alumni.');
                    }
                }
            ];
        }

        $this->validate($rules);

        $data = [
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_selesai' => $this->tgl_selesai,
            'keterangan' => $this->keterangan,
        ];

        if ($this->manualInput) {
            $data['peserta_magang_id'] = null;
            $data['nama_alumni'] = $this->nama_alumni;
            $data['jurusan'] = $this->jurusan;
            $data['nama_sekolah'] = $this->sekolah_nama; // Gunakan sekolah_nama untuk konsistensi
            $data['sekolah_id'] = $this->sekolah_id;
        } else {
            $data['peserta_magang_id'] = $this->peserta_magang_id;
            // PERUBAHAN: Pastikan mengambil peserta dengan status "selesai"
            $peserta = PesertaMagang::with('pengajuan.user.sekolah')
                ->where('id', $this->peserta_magang_id)
                ->where('status', 'selesai')
                ->first();
                
            if ($peserta && $peserta->pengajuan && $peserta->pengajuan->user) {
                $user = $peserta->pengajuan->user;
                $data['nama_alumni'] = $user->nama;
                $data['jurusan'] = $user->jurusan;
                $data['nama_sekolah'] = $user->sekolah->nama ?? '';
                $data['sekolah_id'] = $user->sekolah->id ?? null;
            }
        }

        AlumniMagang::updateOrCreate(['id' => $this->alumni_id], $data);

        session()->flash('message', $this->alumni_id ? 'Data Alumni Magang berhasil diperbarui.' : 'Data Alumni Magang berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
        $this->loadAvailablePeserta(); // Reload available peserta setelah simpan
    }

    // PERUBAHAN: Method untuk me-refresh daftar peserta magang (opsional)
    public function refreshPesertaMagangList()
    {
        // $this->pesertaMagangList = PesertaMagang::with('pengajuan.user')
        //     ->where('status', 'selesai')
        //     ->get();
        $this->loadAvailablePeserta();
    }

    public function openEditModal($id)
    {
        $alumni = AlumniMagang::findOrFail($id);

        $this->alumni_id = $id;
        $this->peserta_magang_id = $alumni->peserta_magang_id;
        $this->nama_alumni = $alumni->nama_alumni;
        $this->jurusan = $alumni->jurusan;
        $this->nama_sekolah = $alumni->nama_sekolah;
        $this->sekolah_id = $alumni->sekolah_id ?? null;
        $this->sekolah_nama = $alumni->nama_sekolah;
        $this->tgl_mulai = $alumni->tgl_mulai;
        $this->tgl_selesai = $alumni->tgl_selesai;
        $this->keterangan = $alumni->keterangan;
        $this->manualInput = $alumni->peserta_magang_id === null;
        $this->isEdit = true;
        $this->isOpen = true;

        // Load available peserta untuk edit (termasuk peserta yang sedang diedit)
        $this->loadAvailablePeserta();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function deleteConfirmed()
    {
        $this->delete($this->deleteId);
        $this->closeDeleteModal();
    }

    public function delete($id)
    {
        AlumniMagang::find($id)?->delete();
        session()->flash('message', 'Data Alumni Magang berhasil dihapus.');
        $this->loadAvailablePeserta(); // Reload available peserta setelah hapus
    }

    public function dismissAlert()
    {
        session()->forget('message');
    }
}