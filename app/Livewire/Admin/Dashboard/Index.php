<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\PesertaMagang;
use App\Models\AlumniMagang;
use App\Models\Sekolah;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $totalUsers;
    public $totalPesertaMagang;
    public $totalAlumni;
    public $totalSekolah;
    public $pengajuanStatus;
    public $pengajuanPerBulan;
    public $pengajuanPerBulanStatus;
    public $pesertaPerSekolah;
    public $chartYear; // Tambahkan property untuk tahun chart

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total counts
        $this->totalUsers = User::count();
        $this->totalPesertaMagang = PesertaMagang::count();
        $this->totalAlumni = AlumniMagang::count();
        $this->totalSekolah = Sekolah::count();

        // Status pengajuan (pie chart data)
        $this->pengajuanStatus = Pengajuan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'total' => $item->total,
                    'color' => $this->getStatusColor($item->status)
                ];
            });

        // // Pengajuan per bulan (bar chart data)
        // $this->pengajuanPerBulan = Pengajuan::select(
        //         DB::raw('MONTH(created_at) as bulan'),
        //         DB::raw('MONTHNAME(created_at) as nama_bulan'),
        //         DB::raw('count(*) as total')
        //     )
        //     ->whereYear('created_at', date('Y'))
        //     ->groupBy('bulan', 'nama_bulan')
        //     ->orderBy('bulan')
        //     ->get();

        // Cari tahun yang memiliki data pengajuan terbanyak
        $availableYear = Pengajuan::whereNotNull('tgl_mulai')
            ->selectRaw('YEAR(tgl_mulai) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('count', 'desc')
            ->first();
            
        $this->chartYear = $availableYear ? $availableYear->year : date('Y');

        // Pengajuan per bulan berdasarkan tgl_mulai (bar chart data)
        $pengajuanRaw = Pengajuan::whereNotNull('tgl_mulai')
            ->whereYear('tgl_mulai', $this->chartYear)
            ->get(['tgl_mulai']);
            
        // Process data manually untuk memastikan kompatibilitas
        $monthlyStats = [];
        foreach ($pengajuanRaw as $pengajuan) {
            $month = (int) date('n', strtotime($pengajuan->tgl_mulai)); // n = 1-12
            if (!isset($monthlyStats[$month])) {
                $monthlyStats[$month] = 0;
            }
            $monthlyStats[$month]++;
        }
        
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $this->pengajuanPerBulan = collect($monthlyStats)->map(function ($total, $bulan) use ($monthNames) {
            return [
                'bulan' => $bulan,
                'nama_bulan' => $monthNames[$bulan],
                'total' => $total
            ];
        })->values();


        // // Peserta magang per sekolah (horizontal bar chart)
        $this->pesertaPerSekolah = PesertaMagang::join('pengajuans', 'peserta_magangs.pengajuan_id', '=', 'pengajuans.id')
                ->join('users', 'pengajuans.user_id', '=', 'users.id')
                ->join('sekolahs', 'users.sekolah_id', '=', 'sekolahs.id')
                ->select('sekolahs.nama', DB::raw('count(*) as total'))
                ->groupBy('sekolahs.id', 'sekolahs.nama')
                ->orderByDesc('total')
                ->limit(10)
                ->get();
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'disetujui' => '#10B981',
            'ditolak' => '#EF4444',
            'menunggu' => '#F59E0B',
            default => '#6B7280'
        };
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index');
    }
}