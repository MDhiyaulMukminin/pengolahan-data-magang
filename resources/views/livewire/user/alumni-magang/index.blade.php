<div>
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-2 text-white">
                                <i class="ri-graduation-cap-line me-2"></i>
                                Daftar Alumni Magang
                            </h4>
                            <p class="card-text mb-0 text-white-50">
                                Lihat daftar alumni yang telah menyelesaikan program magang
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex justify-content-md-end justify-content-start mt-3 mt-md-0">
                                <div class="text-center me-3">
                                    <h3 class="mb-0 text-white">{{ $totalAlumni }}</h3>
                                    <small class="text-white-50">Total Alumni</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="mb-0 text-white">{{ $alumniTahunIni }}</h3>
                                    <small class="text-white-50">Tahun Ini</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Cari nama, jurusan, sekolah...">
                            </div>
                        </div>

                        <!-- Filter Sekolah -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Sekolah</label>
                            <select class="form-select" wire:model.live="filterSekolah">
                                <option value="">Semua Sekolah</option>
                                @foreach($sekolahList as $sekolah)
                                    <option value="{{ $sekolah }}">{{ $sekolah }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tahun -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Tahun</label>
                            <select class="form-select" wire:model.live="filterTahun">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" 
                                        class="btn btn-outline-secondary"
                                        wire:click="clearFilters">
                                    <i class="ri-close-line me-1"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alumni Cards -->
    <div class="row">
        @forelse($alumniMagang as $alumni)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">
                                <i class="ri-user-star-line me-2"></i>
                                Alumni Magang
                            </h6>
                            <span class="badge bg-light text-dark">
                                {{ \Carbon\Carbon::parse($alumni->tgl_mulai)->format('Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark mb-2">
                            {{ $alumni->nama_alumni }}
                        </h5>
                        
                        <div class="mb-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="ri-graduation-cap-line me-1"></i>
                                        <strong>Jurusan:</strong>
                                    </small>
                                    <div class="text-dark">
                                        {{ $this->getJurusan($alumni) }}
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="ri-school-line me-1"></i>
                                        <strong>Sekolah:</strong>
                                    </small>
                                    <div class="text-dark">
                                        {{ $this->getSekolah($alumni) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="ri-calendar-line me-1"></i>
                                    <strong>Mulai:</strong>
                                </small>
                                <div class="text-dark small">
                                    {{ \Carbon\Carbon::parse($alumni->tgl_mulai)->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="ri-calendar-check-line me-1"></i>
                                    <strong>Selesai:</strong>
                                </small>
                                <div class="text-dark small">
                                    {{ \Carbon\Carbon::parse($alumni->tgl_selesai)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        @php
                            $durasi = \Carbon\Carbon::parse($alumni->tgl_mulai)->diffInDays(\Carbon\Carbon::parse($alumni->tgl_selesai)) + 1;
                        @endphp
                        <div class="mb-3">
                            <span class="badge bg-info">
                                <i class="ri-time-line me-1"></i>
                                {{ $durasi }} hari
                            </span>
                        </div>

                        {{-- @if($alumni->keterangan)
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    <strong>Keterangan:</strong>
                                </small>
                                <div class="text-dark small">
                                    {{ Str::limit($alumni->keterangan, 100) }}
                                </div>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ri-group-line ri-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Tidak ada data alumni</h5>
                        <p class="text-muted mb-0">
                            @if($search || $filterSekolah || $filterTahun)
                                Tidak ditemukan alumni dengan kriteria pencarian yang Anda masukkan.
                            @else
                                Belum ada data alumni magang yang tersedia.
                            @endif
                        </p>
                        @if($search || $filterSekolah || $filterTahun)
                            <button type="button" 
                                    class="btn btn-outline-primary mt-3"
                                    wire:click="clearFilters">
                                <i class="ri-close-line me-1"></i>
                                Reset Filter
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alumniMagang->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $alumniMagang->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Loading States -->
    <div wire:loading class="text-center py-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="mt-2">
            <small class="text-muted">Memuat data...</small>
        </div>
    </div>
</div>