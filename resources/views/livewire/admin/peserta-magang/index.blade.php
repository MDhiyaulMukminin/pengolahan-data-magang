<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms="dismissAlert">
            {{ session('message') }}
            <button type="button" class="btn-close" wire:click="dismissAlert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Peserta Magang</h4>
        
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesertaMagangs as $index => $peserta)
                    <tr class="text-center">
                        <th>{{ $pesertaMagangs->firstItem() + $index }}</th>
                        <td class="text-start">{{ $peserta->pengajuan->user->nama }}</td>
                        <td class="text-start">{{ $peserta->pengajuan->user->jurusan }}</td>
                        <td class="text-start">{{ $peserta->pengajuan->user->sekolah->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($peserta->pengajuan->tgl_mulai)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($peserta->pengajuan->tgl_selesai)->format('d-m-Y') }}</td>
                        <td class="text-start">
                            @if($peserta->status == 'menunggu')
                                <span class="badge bg-warning text-dark">
                                    <i class="ri-time-line me-1"></i>{{ ucfirst($peserta->status) }}
                                </span>
                            @elseif($peserta->status == 'aktif')
                                <span class="badge bg-primary">
                                    <i class="ri-play-circle-line me-1"></i>{{ ucfirst($peserta->status) }}
                                </span>
                            @elseif($peserta->status == 'selesai')
                                <span class="badge bg-success">
                                    <i class="ri-check-line me-1"></i>{{ ucfirst($peserta->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="ri-question-line me-1"></i>{{ ucfirst($peserta->status) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" wire:click="openEditModal({{ $peserta->id }})">
                                <i class="ri-edit-2-line me-1"></i>Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $peserta->id }})">
                                <i class="ri-delete-bin-line me-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="ri-inbox-line text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Data tidak ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $pesertaMagangs->links() }}
    </div>

    <!-- Modal Form -->
    @if($isOpen)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-line me-2"></i>Edit Data Peserta Magang
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="ri-flag-line me-1"></i>Status
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" wire:model="status">
                                <option value="">Pilih Status</option>
                                <option value="menunggu">
                                    Menunggu
                                </option>
                                <option value="aktif">
                                    Aktif
                                </option>
                                <option value="selesai">
                                    Selesai
                                </option>
                            </select>
                            @error('status') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        <i class="ri-close-line me-1"></i>Tutup
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="update">
                        <i class="ri-save-line me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-delete-bin-line me-2 text-danger"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeDeleteModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-error-warning-line text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Apakah Anda yakin?</h4>
                    <p class="mb-0">Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                        <i class="ri-delete-bin-line me-1"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>