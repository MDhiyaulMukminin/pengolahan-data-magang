<?php

namespace App\Livewire\Admin\Laporan;

use App\Models\Sekolah;
use Livewire\Component;
use App\Models\Pengajuan;
use App\Models\AlumniMagang;
use App\Models\PesertaMagang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $jenis_laporan = 'peserta'; // peserta atau alumni
    public $filter_tanggal_mulai;
    public $filter_tanggal_selesai;
    public $filter_sekolah;

    // Data untuk ditampilkan
    public $data_laporan = [];
    public $total_data = 0;

    // List sekolah untuk dropdown
    public $sekolah_list = [];

    public function mount()
    {
        $this->sekolah_list = Sekolah::where('status', 'aktif')->get();
        $this->loadData();
    }

    public function updatedJenisLaporan()
    {
        $this->resetFilters();
        $this->loadData();
    }

    public function resetFilters()
    {
        $this->filter_tanggal_mulai = null;
        $this->filter_tanggal_selesai = null;
        $this->filter_sekolah = null;
        $this->loadData();
    }

    public function applyFilter()
    {
        $this->loadData();
    }

    public function loadData()
    {
        if ($this->jenis_laporan === 'peserta') {
            $this->loadPesertaData();
        } else {
            $this->loadAlumniData();
        }
    }

    private function loadPesertaData()
    {
        $query = PesertaMagang::with(['pengajuan.user.sekolah', 'pengajuan.user'])
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'disetujui');
            });

        // Apply filters
        if ($this->filter_tanggal_mulai) {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('tgl_mulai', '>=', $this->filter_tanggal_mulai);
            });
        }

        if ($this->filter_tanggal_selesai) {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('tgl_selesai', '<=', $this->filter_tanggal_selesai);
            });
        }

        if ($this->filter_sekolah) {
            $query->whereHas('pengajuan.user.sekolah', function ($q) {
                $q->where('id', $this->filter_sekolah);
            });
        }

        $this->data_laporan = $query->get()->map(function ($peserta) {
            return [
                'nama' => $peserta->pengajuan->user->nama,
                'sekolah' => $peserta->pengajuan->user->sekolah->nama,
                'jurusan' => $peserta->pengajuan->user->jurusan,
                'tgl_mulai' => $peserta->pengajuan->tgl_mulai,
                'tgl_selesai' => $peserta->pengajuan->tgl_selesai,
            ];
        })->toArray();

        $this->total_data = count($this->data_laporan);
    }

    private function loadAlumniData()
    {
        $query = AlumniMagang::with(['pesertaMagang.pengajuan.user.sekolah']);

        // Apply filters
        if ($this->filter_tanggal_mulai) {
            $query->where('tgl_mulai', '>=', $this->filter_tanggal_mulai);
        }

        if ($this->filter_tanggal_selesai) {
            $query->where('tgl_selesai', '<=', $this->filter_tanggal_selesai);
        }

        // Fix untuk filter sekolah alumni
        if ($this->filter_sekolah) {
            $sekolah = Sekolah::find($this->filter_sekolah);
            if ($sekolah) {
                // Gunakan nama sekolah langsung atau melalui relasi
                $query->where(function ($q) use ($sekolah) {
                    $q->where('nama_sekolah', $sekolah->nama)
                      ->orWhereHas('pesertaMagang.pengajuan.user.sekolah', function ($subQ) use ($sekolah) {
                          $subQ->where('id', $sekolah->id);
                      });
                });
            }
        }

        $this->data_laporan = $query->get()->map(function ($alumni) {
            return [
                'nama' => $alumni->nama_alumni,
                'sekolah' => $alumni->nama_sekolah,
                'jurusan' => $alumni->jurusan,
                'tgl_mulai' => $alumni->tgl_mulai,
                'tgl_selesai' => $alumni->tgl_selesai,
            ];
        })->toArray();

        $this->total_data = count($this->data_laporan);
    }

    public function exportPDF()
    {
        // Ambil ulang data sesuai jenis laporan dan filter
        $this->loadData();

        // Tentukan jenis laporan untuk judul
        $jenisLaporan = $this->jenis_laporan === 'peserta' ? 'Peserta' : 'Alumni';

        // Ambil nama sekolah dari filter (jika ada)
        $namaSekolah = 'Semua Sekolah';
        if ($this->filter_sekolah) {
            $sekolah = Sekolah::find($this->filter_sekolah);
            if ($sekolah) {
                $namaSekolah = $sekolah->nama;
            }
        }

        // Siapkan data untuk view PDF
        $data = [
            'jenis_laporan' => $jenisLaporan,
            'data_laporan' => $this->data_laporan,
            'total_data' => $this->total_data,
            'filter_tanggal_mulai' => $this->filter_tanggal_mulai,
            'filter_tanggal_selesai' => $this->filter_tanggal_selesai,
            'filter_sekolah' => $namaSekolah,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'sekolah_list' => $this->sekolah_list,
        ];

        // Generate PDF dari view
        $pdf = Pdf::loadView('admin.laporan.pdf', $data);

        // Nama file berdasarkan jenis dan tanggal
        $filename = 'Laporan_' . ucfirst($this->jenis_laporan) . '_Magang_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Download streaming
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }


    public function render()
    {
        return view('livewire.admin.laporan.index');
    }
}