<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms="dismissAlert">
            {{ session('message') }}
            <button type="button" class="btn-close" wire:click="dismissAlert" aria-label="Close"></button>
        </div>
    @endif

    <h4 class="mb-3">Daftar Sekolah</h4>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-primary" wire:click="openModal">
            <i class="ri-add-line"></i> Tambah
        </button>
        
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
                    <th>Sekolah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sekolahs as $index => $sekolah)
                    <tr class="text-center">
                        <th>{{ $sekolahs->firstItem() + $index }}</th>
                        <td class="text-start">{{ $sekolah->nama }}</td>
                        <td>
                            @if($sekolah->status == 'aktif')
                                <span class="badge bg-success"><i class="ri-check-line me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-warning"><i class="ri-time-line me-1"></i>Menunggu</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" wire:click="edit({{ $sekolah->id }})">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="deleteConfirm({{ $sekolah->id }})">
                                <i class="ri-delete-bin-line"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $sekolahs->links() }}
    </div>


     <!-- Modal Form -->
     @if($isOpen)
     <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" aria-labelledby="modalForm" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="modalForm">{{ $sekolah_id ? 'Edit Sekolah' : 'Tambah Sekolah' }}</h5>
                     <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form wire:submit.prevent="store">
                         <div class="row">
                             <div class="col-md-12 mb-3">
                                 <label for="nama" class="form-label">Nama Sekolah</label>
                                 <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" wire:model="nama" placeholder="Masukkan nama sekolah">
                                 @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                             </div>
                             <div class="col-md-12 mb-3">
                                 <label for="status" class="form-label">Status</label>
                                 <select class="form-select @error('status') is-invalid @enderror" id="status" wire:model="status">
                                     <option value="">Pilih Status</option>
                                     <option value="aktif">Aktif</option>
                                     <option value="menunggu">Menunggu</option>
                                 </select>
                                 @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                <i class="ri-close-line me-1"></i>Tutup</button>
                             <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Simpan</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
     @endif


    <!-- Delete Confirmation Modal -->
    @if($isDelete)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-delete-bin-line me-2 text-danger"></i>Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-error-warning-line text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Apakah Anda yakin?</h4>
                    <p class="mb-0">Data sekolah ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>