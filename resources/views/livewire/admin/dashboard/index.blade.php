<div>
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Selamat Datang, Admin!</h4>
                            <p class="text-muted mb-0">Berikut adalah ringkasan data sistem magang hari ini.</p>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Users</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $totalUsers }}">0</span>
                                    </h4>
                                    <a href="{{ route('admin.user') }}" class="text-decoration-underline">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Data Peserta Magang</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $totalPesertaMagang }}">0</span>
                                    </h4>
                                    <a href="{{ route('admin.peserta-magang') }}" class="text-decoration-underline">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Data Alumni Magang</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $totalAlumni }}">0</span>
                                    </h4>
                                    <a href="{{ route('admin.alumni-magang') }}" class="text-decoration-underline">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Total Sekolah</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $totalSekolah }}">0</span>
                                    </h4>
                                    <a href="{{ route('admin.sekolah') }}" class="text-decoration-underline">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Statistik Pengajuan per Bulan <small class="text-muted">({{ $chartYear }})</small></h4>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-light-subtle">
                            <div class="row g-0 text-center">
                                @if($pengajuanStatus->count() > 0)
                                    @foreach($pengajuanStatus as $index => $status)
                                        <div class="col-6 col-sm-{{ $pengajuanStatus->count() == 3 ? '4' : '3' }}">
                                            <div class="p-3 border border-dashed {{ $index == 0 ? 'border-start-0' : '' }} {{ $loop->last ? 'border-end-0' : '' }}">
                                                <h5 class="mb-1">
                                                    <span class="counter-value" data-target="{{ $status['total'] }}">0</span>
                                                </h5>
                                                <p class="text-muted mb-0">{{ $status['status'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="p-3">
                                            <p class="text-muted mb-0">Belum ada data pengajuan</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body p-0 pb-2">
                            <div class="w-100">
                                <div id="pengajuan_chart" 
                                    data-pengajuan="{{ json_encode($pengajuanPerBulan) }}"
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Status Pengajuan</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="status_chart" class="apex-charts" dir="ltr"></div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Top 10 Sekolah dengan Peserta Magang Terbanyak</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            @if($pesertaPerSekolah->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama Sekolah</th>
                                                <th scope="col">Persentase</th>
                                                <th scope="col">Jumlah Peserta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalPeserta = $pesertaPerSekolah->sum('total');
                                            @endphp
                                            @foreach($pesertaPerSekolah as $index => $sekolah)
                                                @php
                                                    $persentase = $totalPeserta > 0 ? round(($sekolah->total / $totalPeserta) * 100, 1) : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $sekolah->nama }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-primary" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $persentase }}%" 
                                                                         aria-valuenow="{{ $persentase }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-shrink-0 ms-2">
                                                                <span class="text-muted fs-12">{{ $persentase }}%</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary fs-12">{{ $sekolah->total }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <p class="text-muted">Belum ada data peserta magang</p>
                                </div>
                            @endif
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard script loaded');
    
    // Debug data
    const pengajuanData = @json($pengajuanPerBulan);
    const statusData = @json($pengajuanStatus);
    
    console.log('Pengajuan Data:', pengajuanData);
    console.log('Status Data:', statusData);
    
    // Inisialisasi Chart Pengajuan per Bulan
    initPengajuanChart();
    
    // Inisialisasi Chart Status Pengajuan
    initStatusChart();
});

function initPengajuanChart() {
    const chartElement = document.querySelector('#pengajuan_chart');
    if (!chartElement) {
        console.error('Chart element #pengajuan_chart not found');
        return;
    }
    
    const pengajuanData = @json($pengajuanPerBulan);
    console.log('Processing pengajuan data:', pengajuanData);
    
    if (!pengajuanData || pengajuanData.length === 0) {
        chartElement.innerHTML = '<div class="text-center p-4"><p class="text-muted">Belum ada data pengajuan untuk tahun ini</p></div>';
        return;
    }
    
    // Pastikan semua bulan tampil (1-12) dengan nilai 0 jika tidak ada data
    const allMonths = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    const monthlyData = Array(12).fill(0);
    
    // Fill data yang ada
    pengajuanData.forEach(item => {
        if (item.bulan >= 1 && item.bulan <= 12) {
            monthlyData[item.bulan - 1] = item.total;
        }
    });
    
    const options = {
        series: [{
            name: 'Jumlah Pengajuan',
            data: monthlyData
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            },
        },
        dataLabels: { enabled: false },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: { 
            categories: allMonths,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: { 
            title: { text: 'Jumlah Pengajuan' },
            min: 0
        },
        fill: { opacity: 1 },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " pengajuan"
                }
            }
        },
        colors: ['#556ee6'],
        grid: {
            borderColor: '#f1f1f1'
        }
    };
    
    try {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
        console.log('Pengajuan chart rendered successfully');
    } catch (error) {
        console.error('Error rendering pengajuan chart:', error);
        chartElement.innerHTML = '<div class="text-center p-4"><p class="text-danger">Error loading chart</p></div>';
    }
}

function initStatusChart() {
    const statusData = @json($pengajuanStatus);
    const chartElement = document.querySelector('#status_chart');
    
    if (!chartElement) {
        console.error('Chart element #status_chart not found');
        return;
    }
    
    console.log('Processing status data:', statusData);
    
    if (!statusData || statusData.length === 0) {
        chartElement.innerHTML = '<div class="text-center p-4"><p class="text-muted">Belum ada data status pengajuan</p></div>';
        return;
    }
    
    const labels = statusData.map(item => item.status);
    const series = statusData.map(item => item.total);
    const colors = statusData.map(item => item.color);
    
    const options = {
        series: series,
        chart: {
            type: 'donut',
            height: 350
        },
        labels: labels,
        colors: colors,
        legend: { 
            position: 'bottom',
            fontSize: '12px'
        },
        plotOptions: {
            pie: {
                donut: { 
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex];
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " pengajuan"
                }
            }
        }
    };
    
    try {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
        console.log('Status chart rendered successfully');
    } catch (error) {
        console.error('Error rendering status chart:', error);
        chartElement.innerHTML = '<div class="text-center p-4"><p class="text-danger">Error loading chart</p></div>';
    }
}
</script>
@endpush