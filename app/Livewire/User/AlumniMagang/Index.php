<?php

namespace App\Livewire\User\AlumniMagang;

use App\Models\AlumniMagang;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterSekolah = '';
    public $filterTahun = '';

    protected $listeners = ['searchUpdated'];
    protected $queryString = [
        'search' => ['except' => ''],
        'filterSekolah' => ['except' => ''],
        'filterTahun' => ['except' => '']
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->filterSekolah = request()->query('filterSekolah', $this->filterSekolah);
        $this->filterTahun = request()->query('filterTahun', $this->filterTahun);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterSekolah()
    {
        $this->resetPage();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
    }

    public function searchUpdated($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterSekolah = '';
        $this->filterTahun = '';
        $this->resetPage();
    }

    // Helper methods untuk mendapatkan data yang benar
    public function getJurusan($alumni)
    {
        if ($alumni->peserta_magang_id && 
            $alumni->pesertaMagang && 
            $alumni->pesertaMagang->pengajuan && 
            $alumni->pesertaMagang->pengajuan->user) {
            return $alumni->pesertaMagang->pengajuan->user->jurusan;
        }
        return $alumni->jurusan;
    }

    public function getSekolah($alumni)
    {
        if ($alumni->peserta_magang_id && 
            $alumni->pesertaMagang && 
            $alumni->pesertaMagang->pengajuan && 
            $alumni->pesertaMagang->pengajuan->user && 
            $alumni->pesertaMagang->pengajuan->user->sekolah) {
            return $alumni->pesertaMagang->pengajuan->user->sekolah->nama;
        }
        return $alumni->nama_sekolah;
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        $query = AlumniMagang::with(['pesertaMagang.pengajuan.user.sekolah']);

        // Filter pencarian
        if ($this->search) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_alumni', 'like', $searchTerm)
                  ->orWhere('jurusan', 'like', $searchTerm)
                  ->orWhere('nama_sekolah', 'like', $searchTerm)
                  ->orWhere('keterangan', 'like', $searchTerm);
            })
            ->orWhereHas('pesertaMagang.pengajuan.user', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm)
                  ->orWhere('jurusan', 'like', $searchTerm);
            })
            ->orWhereHas('pesertaMagang.pengajuan.user.sekolah', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm);
            });
        }

        // Filter sekolah
        if ($this->filterSekolah) {
            $query->where(function ($q) {
                $q->where('nama_sekolah', 'like', '%' . $this->filterSekolah . '%')
                  ->orWhereHas('pesertaMagang.pengajuan.user.sekolah', function ($subQ) {
                      $subQ->where('nama', 'like', '%' . $this->filterSekolah . '%');
                  });
            });
        }

        // Filter tahun
        if ($this->filterTahun) {
            $query->whereYear('tgl_mulai', $this->filterTahun);
        }

        $alumniMagang = $query->orderBy('created_at', 'desc')
                            ->paginate(12);

        // Sekolah dari manual input dan relasi
        $sekolahFromManual = AlumniMagang::select('nama_sekolah')
                                       ->whereNotNull('nama_sekolah')
                                       ->where('nama_sekolah', '!=', '')
                                       ->pluck('nama_sekolah');

        $sekolahFromRelasi = AlumniMagang::whereNotNull('peserta_magang_id')
                                       ->with('pesertaMagang.pengajuan.user.sekolah')
                                       ->get()
                                       ->pluck('pesertaMagang.pengajuan.user.sekolah.nama')
                                       ->filter();

        $sekolahList = $sekolahFromManual->merge($sekolahFromRelasi)
                                       ->unique()
                                       ->sort()
                                       ->values();

        $tahunList = AlumniMagang::selectRaw('YEAR(tgl_mulai) as tahun')
                                ->distinct()
                                ->whereNotNull('tgl_mulai')
                                ->orderBy('tahun', 'desc')
                                ->pluck('tahun');

        return view('livewire.user.alumni-magang.index', [
            'alumniMagang' => $alumniMagang,
            'sekolahList' => $sekolahList,
            'tahunList' => $tahunList,
            'totalAlumni' => AlumniMagang::count(),
            'alumniTahunIni' => AlumniMagang::whereYear('tgl_mulai', now()->year)->count()
        ]);
    }
}