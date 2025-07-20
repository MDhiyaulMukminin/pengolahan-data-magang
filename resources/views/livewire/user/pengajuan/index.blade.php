<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Pengajuan Magang</h1>
                <p class="mb-0 text-muted">Kelola pengajuan magang Anda</p>
            </div>
            <button type="button" class="btn btn-primary" wire:click="openCreateModal">
                <i class="ri-add-line me-2"></i>Ajukan Magang
            </button>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" wire:click="dismissAlert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" wire:click="dismissAlert"></button>
            </div>
        @endif

        <!-- Pengajuan List -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Magang</h6>
            </div>
            <div class="card-body">
                @if($pengajuans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Sekolah</th>
                                    <th>Periode Magang</th>
                                    <th>Status</th>
                                    <th>Surat Balasan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuans as $index => $pengajuan)
                                <tr>
                                    <td>{{ $pengajuans->firstItem() + $index }}</td>
                                    <td>{{ $pengajuan->user->nama }}</td>
                                    <td>{{ $pengajuan->user->jurusan }}</td>
                                    <td>{{ $pengajuan->user->sekolah->nama ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pengajuan->tgl_mulai)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($pengajuan->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">
                                                <i class="ri-time-line me-1"></i>Menunggu
                                            </span>
                                        @elseif($pengajuan->status == 'disetujui')
                                            <span class="badge bg-success">
                                                <i class="ri-check-line me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="ri-close-line me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($pengajuan->surat_balasan)
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ Storage::url($pengajuan->surat_balasan) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   target="_blank" 
                                                   title="Lihat Surat Balasan">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ Storage::url($pengajuan->surat_balasan) }}" 
                                                   class="btn btn-sm btn-success" 
                                                   download 
                                                   title="Download Surat Balasan">
                                                    <i class="ri-download-line"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="ri-file-forbid-line me-1"></i>Tidak Tersedia
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" 
                                                wire:click="openDetailModal({{ $pengajuan->id }})">
                                            <i class="ri-eye-line"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pengajuans->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-folder-open-line ri-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada pengajuan magang</h5>
                        <p class="text-muted">Klik tombol "Ajukan Magang" untuk membuat pengajuan baru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-line me-2"></i>Pengajuan Magang Baru
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeCreateModal"></button>
                </div>
                <form wire:submit.prevent="submit">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" 
                                       wire:model="tgl_mulai">
                                @error('tgl_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tgl_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tgl_selesai') is-invalid @enderror" 
                                       wire:model="tgl_selesai">
                                @error('tgl_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="surat_pengantar" class="form-label">Surat Pengantar <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('surat_pengantar') is-invalid @enderror" 
                                   wire:model="surat_pengantar" accept=".pdf">
                            <div class="form-text">Format: PDF, Maksimal 2MB</div>
                            @error('surat_pengantar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeCreateModal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove>
                                <i class="ri-send-plane-line me-2"></i>Kirim Pengajuan
                            </span>
                            <span wire:loading>
                                <i class="ri-loader-line ri-spin me-2"></i>Mengirim...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedPengajuan)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-information-line me-2"></i>Detail Pengajuan Magang
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama:</label>
                            <p class="form-control-plaintext">{{ $selectedPengajuan->user->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p class="form-control-plaintext">
                                @if($selectedPengajuan->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">
                                        <i class="ri-time-line me-1"></i>Menunggu
                                    </span>
                                @elseif($selectedPengajuan->status == 'disetujui')
                                    <span class="badge bg-success">
                                        <i class="ri-check-line me-1"></i>Disetujui
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="ri-close-line me-1"></i>Ditolak
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jurusan:</label>
                            <p class="form-control-plaintext">{{ $selectedPengajuan->user->jurusan }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sekolah:</label>
                            <p class="form-control-plaintext">{{ $selectedPengajuan->user->sekolah->nama ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai:</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($selectedPengajuan->tgl_mulai)->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Selesai:</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($selectedPengajuan->tgl_selesai)->format('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Surat Pengantar:</label>
                            @if($selectedPengajuan->surat_pengantar)
                                <p class="form-control-plaintext">
                                    <div class="d-flex gap-2">
                                        <a href="{{ Storage::url($selectedPengajuan->surat_pengantar) }}" 
                                           class="btn btn-sm btn-info" 
                                           target="_blank">
                                            <i class="ri-eye-line"></i> Lihat
                                        </a>
                                        <a href="{{ Storage::url($selectedPengajuan->surat_pengantar) }}" 
                                           class="btn btn-sm btn-success" 
                                           download>
                                            <i class="ri-download-line"></i> Download
                                        </a>
                                    </div>
                                </p>
                            @else
                                <p class="form-control-plaintext text-muted">Tidak ada file</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Surat Balasan:</label>
                            @if($selectedPengajuan->surat_balasan)
                                <p class="form-control-plaintext">
                                    <div class="d-flex gap-2">
                                        <a href="{{ Storage::url($selectedPengajuan->surat_balasan) }}" 
                                           class="btn btn-sm btn-info" 
                                           target="_blank">
                                            <i class="ri-eye-line"></i> Lihat
                                        </a>
                                        <a href="{{ Storage::url($selectedPengajuan->surat_balasan) }}" 
                                           class="btn btn-sm btn-success" 
                                           download>
                                            <i class="ri-download-line"></i> Download
                                        </a>
                                        <span class="badge bg-success ms-2">
                                            <i class="ri-file-pdf-line me-1"></i>Tersedia
                                        </span>
                                    </div>
                                </p>
                            @else
                                <p class="form-control-plaintext">
                                    <span class="badge bg-secondary">
                                        <i class="ri-file-forbid-line me-1"></i>Belum Tersedia
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pengajuan:</label>
                        <p class="form-control-plaintext">{{ $selectedPengajuan->created_at->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>