<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms="dismissAlert">
            {{ session('message') }}
            <button type="button" class="btn-close" wire:click="dismissAlert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Pengajuan Magang</h4>
        
        <div class="input-group w-25">
            <input 
                type="text" 
                class="form-control form-control-sm" 
                placeholder="Search..." 
                wire:model.live.debounce.300ms="search"
            >
            @if(!empty($search))
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('search', '')">
                    <i class="ri-close-line"></i> Clear
                </button>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $statusFilter == 'semua' ? 'active' : '' }}" href="#" wire:click.prevent="setStatusFilter('semua')">
                        Semua <span class="badge bg-secondary">{{ $counters['semua'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $statusFilter == 'menunggu' ? 'active' : '' }}" href="#" wire:click.prevent="setStatusFilter('menunggu')">
                        Menunggu <span class="badge bg-warning">{{ $counters['menunggu'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $statusFilter == 'disetujui' ? 'active' : '' }}" href="#" wire:click.prevent="setStatusFilter('disetujui')">
                        Disetujui <span class="badge bg-success">{{ $counters['disetujui'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $statusFilter == 'ditolak' ? 'active' : '' }}" href="#" wire:click.prevent="setStatusFilter('ditolak')">
                        Ditolak <span class="badge bg-danger">{{ $counters['ditolak'] }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Sekolah</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                            <th>Surat Balasan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $index => $pengajuan)
                            <tr class="text-center">
                                <th>{{ $pengajuans->firstItem() + $index }}</th>
                                <td class="text-start">{{ $pengajuan->user->nama }}</td>
                                <td class="text-start">{{ $pengajuan->user->jurusan }}</td>
                                <td class="text-start">{{ $pengajuan->user->sekolah->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->tgl_mulai)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->format('d-m-Y') }}</td>
                                <td>
                                    @if($pengajuan->status == 'menunggu')
                                        <span class="badge bg-warning"><i class="ri-time-line me-1"></i>Menunggu</span>
                                    @elseif($pengajuan->status == 'disetujui')
                                        <span class="badge bg-success"><i class="ri-check-line me-1"></i>Disetujui</span>
                                    @elseif($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger"><i class="ri-close-line me-1"></i>Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengajuan->surat_balasan)
                                        <a href="{{ Storage::url($pengajuan->surat_balasan) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="ri-file-pdf-line"></i> Lihat
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" wire:click="openDetailModal({{ $pengajuan->id }})">
                                        <i class="ri-eye-line"></i> Detail
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" wire:click="openResponseModal({{ $pengajuan->id }})">
                                        <i class="ri-reply-line"></i> Respon
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengajuan</h5>
                    <button type="button" class="btn-close" wire:click="closeDetailModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($selectedPengajuan)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama</label>
                                <p>{{ $selectedPengajuan->user->nama }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p>{{ $selectedPengajuan->user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jurusan</label>
                                <p>{{ $selectedPengajuan->user->jurusan }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sekolah</label>
                                <p>{{ $selectedPengajuan->user->sekolah->nama }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Periode Magang</label>
                                <p>{{ \Carbon\Carbon::parse($selectedPengajuan->tgl_mulai)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($selectedPengajuan->tgl_selesai)->format('d-m-Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p>
                                    @if($selectedPengajuan->status == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($selectedPengajuan->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($selectedPengajuan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Surat Pengantar</label>
                                <p>
                                    <a href="{{ Storage::url($selectedPengajuan->surat_pengantar) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="ri-file-pdf-line"></i> Lihat Surat
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Response Modal -->
    @if($showResponseModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Respon Pengajuan</h5>
                    <button type="button" class="btn-close" wire:click="closeResponseModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($selectedPengajuan)
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <p>{{ $selectedPengajuan->user->nama }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jurusan</label>
                            <p>{{ $selectedPengajuan->user->jurusan }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sekolah</label>
                            <p>{{ $selectedPengajuan->user->sekolah->nama }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select w-50 @error('status') is-invalid @enderror" id="status" wire:model="status">
                                <option value="">Pilih Status</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="surat_balasan" class="form-label fw-bold">Surat Balasan (PDF)</label>
                            <input type="file" class="form-control @error('surat_balasan') is-invalid @enderror" id="surat_balasan" wire:model="surat_balasan" accept="application/pdf">
                            @error('surat_balasan') <span class="text-danger">{{ $message }}</span> @enderror
                            
                            <div wire:loading wire:target="surat_balasan" class="mt-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="text-primary ms-1">Uploading...</span>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeResponseModal">
                        <i class="ri-close-line me-1"></i>Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click="submitResponse">
                        <i class="ri-save-line me-1"></i>Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>