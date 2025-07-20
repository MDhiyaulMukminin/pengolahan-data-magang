<div>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white"><i class="ri-file-text-line me-2"></i>Laporan Magang</h5>
                    </div>
                    <div class="card-body">
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $jenis_laporan === 'peserta' ? 'active' : '' }}" 
                                    wire:click="$set('jenis_laporan', 'peserta')" 
                                    type="button">
                                <i class="ri-group-line me-1"></i>Peserta Magang
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $jenis_laporan === 'alumni' ? 'active' : '' }}" 
                                    wire:click="$set('jenis_laporan', 'alumni')" 
                                    type="button">
                                <i class="ri-graduation-cap-line me-1"></i>Alumni Magang
                            </button>
                        </li>
                    </ul>

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="ri-filter-line me-2"></i>Filter Laporan
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal Mulai</label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   wire:model="filter_tanggal_mulai">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal Selesai</label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   wire:model="filter_tanggal_selesai">
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label class="form-label">Sekolah</label>
                                            <select class="form-select" wire:model="filter_sekolah">
                                                <option value="">Semua Sekolah</option>
                                                @foreach($sekolah_list as $sekolah)
                                                    <option value="{{ $sekolah->id }}">{{ $sekolah->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-2 d-flex align-items-end">
                                            <div class="btn-group w-100">
                                                <button type="button" 
                                                        class="btn btn-primary" 
                                                        wire:click="applyFilter">
                                                    <i class="ri-search-line me-1"></i>Filter
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-secondary" 
                                                        wire:click="resetFilters">
                                                    <i class="ri-refresh-line me-1"></i>Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        Menampilkan {{ $total_data }} {{ $jenis_laporan === 'peserta' ? 'Peserta' : 'Alumni' }} Magang
                                    </h6>
                                </div>
                                <div>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            wire:click="exportPDF()"
                                            {{ $total_data === 0 ? 'disabled' : '' }}>
                                        <i class="ri-file-pdf-line me-2"></i>Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Sekolah</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data_laporan as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data['nama'] }}</td>
                                        <td>{{ $data['jurusan'] }}</td>
                                        <td>{{ $data['sekolah'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data['tgl_mulai'])->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data['tgl_selesai'])->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="ri-inbox-line" style="font-size: 3rem;"></i><br><br>
                                            Tidak ada data {{ $jenis_laporan === 'peserta' ? 'peserta' : 'alumni' }} magang yang ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Card -->
                    @if($total_data > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-primary">
                                            Ringkasan Laporan
                                        </h5>
                                        <p class="card-text mb-0">
                                            <strong>Total {{ $jenis_laporan === 'peserta' ? 'Peserta' : 'Alumni' }} Magang: 
                                                <span class="text-primary">{{ $total_data }}</span>
                                            </strong>
                                        </p>
                                        @if($filter_sekolah)
                                            <small class="text-muted">
                                                Sekolah: {{ $sekolah_list->find($filter_sekolah)->nama ?? 'Tidak ditemukan' }}
                                            </small>
                                        @endif
                                        @if($filter_tanggal_mulai || $filter_tanggal_selesai)
                                            <br>
                                            <small class="text-muted">
                                                Periode: 
                                                {{ $filter_tanggal_mulai ? \Carbon\Carbon::parse($filter_tanggal_mulai)->format('d/m/Y') : 'Awal' }}
                                                s/d 
                                                {{ $filter_tanggal_selesai ? \Carbon\Carbon::parse($filter_tanggal_selesai)->format('d/m/Y') : 'Akhir' }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>