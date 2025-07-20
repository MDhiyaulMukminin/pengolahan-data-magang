<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms="dismissAlert">
            {{ session('message') }}
            <button type="button" class="btn-close" wire:click="dismissAlert" aria-label="Close"></button>
        </div>
    @endif

    <h4 class="mb-3">Daftar Pengguna</h4>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-primary" wire:click="openModal">
            <i class="ri-add-line me-1"></i>Tambah</button>
        
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
                    <th>Email</th>
                    <th>Sekolah</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="text-center">
                        <th>{{ $users->firstItem() + $index }}</th>
                        <td class="text-start">{{ $user->nama }}</td>
                        <td class="text-start">{{ $user->email }}</td>
                        <td class="text-start">{{ $user->sekolah->nama ?? '-' }}</td>
                        <td>
                            @if($user->role_id == 1)
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status_akun == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @elseif($user->status_akun == 'belum lengkapi profile')
                                <span class="badge bg-warning">Belum Lengkapi Profil</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="view({{ $user->id }})"><i class="ri-eye-line me-1"></i> Detail</button>
                            <button type="button" class="btn btn-warning btn-sm" wire:click="edit({{ $user->id }})"><i class="ri-edit-2-line me-1"></i>Edit</button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="deleteConfirm({{ $user->id }})"><i class="ri-delete-bin-line me-1"></i>Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>

     <!-- Modal Form -->
     @if($isOpen)
     <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" aria-labelledby="modalForm" aria-hidden="true">
         <div class="modal-dialog {{ $isEdit ? 'modal-lg' : '' }}">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="modalForm">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h5>
                     <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form wire:submit.prevent="store">
                         <div class="row">
                             <!-- Form untuk Tambah User (Sederhana) -->
                             @if(!$isEdit)
                                 <div class="col-12 mb-3">
                                     <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" wire:model="nama">
                                     @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-12 mb-3">
                                     <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                     <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email">
                                     @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-12 mb-3">
                                     <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                     <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" wire:model="role_id">
                                         <option value="">Pilih Role</option>
                                         <option value="1">Admin</option>
                                         <option value="2">User</option>
                                     </select>
                                     @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-12 mb-3">
                                     <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                     <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password">
                                     @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                             @else
                                 <!-- Form untuk Edit User (Lengkap) -->
                                 <div class="col-md-6 mb-3">
                                     <label for="no_induk" class="form-label">No. Induk</label>
                                     <input type="text" class="form-control @error('no_induk') is-invalid @enderror" id="no_induk" wire:model="no_induk">
                                     @error('no_induk') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" wire:model="nama">
                                     @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                     <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email">
                                     @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="no_whatsapp" class="form-label">No. WhatsApp</label>
                                     <input type="text" class="form-control @error('no_whatsapp') is-invalid @enderror" id="no_whatsapp" wire:model="no_whatsapp">
                                     @error('no_whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 
                                 <div class="col-md-6 mb-3 position-relative">
                                    <label for="sekolah_id" class="form-label">Sekolah</label>
                                    
                                    <input 
                                        type="text" 
                                        class="form-control @error('sekolah_nama') is-invalid @enderror @error('sekolah_id') is-invalid @enderror" 
                                        placeholder="Ketik nama sekolah untuk mencari..." 
                                        wire:model.live.debounce.300ms="sekolah_nama"
                                        wire:keydown.arrow-down="selectNext"
                                        wire:keydown.arrow-up="selectPrevious"
                                        wire:keydown.enter.prevent="chooseSekolah"
                                        wire:keydown.escape="hideDropdown"
                                        wire:focus="focusSekolah"
                                        autocomplete="off"
                                    >

                                    @error('sekolah_nama') <span class="text-danger">{{ $message }}</span> @enderror
                                    @error('sekolah_id') <span class="text-danger">{{ $message }}</span> @enderror

                                    @if($showDropdown && count($filteredSekolah) > 0)
                                        <ul class="list-group position-absolute w-100 shadow-lg border" style="z-index: 1050; max-height: 200px; overflow-y: auto; top: 100%;">
                                            @foreach($filteredSekolah as $index => $sekolah)
                                                <li class="list-group-item list-group-item-action {{ $highlightIndex === $index ? 'active' : '' }}"
                                                    wire:click="selectSekolah({{ $sekolah->id }})"
                                                    wire:mouseenter="$set('highlightIndex', {{ $index }})"
                                                    style="cursor: pointer; padding: 8px 12px;">
                                                    {{ $sekolah->nama }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if($showDropdown && strlen($sekolah_nama) >= 2 && count($filteredSekolah) === 0)
                                        <ul class="list-group position-absolute w-100 shadow-lg border" style="z-index: 1050; top: 100%;">
                                            <li class="list-group-item text-muted text-center" style="padding: 8px 12px;">
                                                Sekolah tidak ditemukan
                                            </li>
                                        </ul>
                                    @endif

                                    <!-- Hidden input untuk menyimpan ID sekolah -->
                                    <input type="hidden" wire:model="sekolah_id">
                                 </div>
                                                           
                                 <div class="col-md-6 mb-3">
                                     <label for="jurusan" class="form-label">Jurusan</label>
                                     <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" wire:model="jurusan">
                                     @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                     <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" wire:model="role_id">
                                         <option value="">Pilih Role</option>
                                         <option value="1">Admin</option>
                                         <option value="2">User</option>
                                     </select>
                                     @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="status_akun" class="form-label">Status Akun <span class="text-danger">*</span></label>
                                     <select class="form-select @error('status_akun') is-invalid @enderror" id="status_akun" wire:model="status_akun">
                                         <option value="">Pilih Status</option>
                                         <option value="aktif">Aktif</option>
                                         <option value="belum lengkapi profile">Belum Lengkapi Profil</option>
                                         <option value="nonaktif">Nonaktif</option>
                                     </select>
                                     @error('status_akun') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                                 <div class="col-md-12 mb-3">
                                     <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                     <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password">
                                     @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                 </div>
                             @endif
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

    <!-- Modal View -->
    @if($isView)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengguna</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" width="30%">No. Induk</th>
                                    <td>: {{ $no_induk ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Nama</th>
                                    <td>: {{ $nama }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>: {{ $email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">No. WhatsApp</th>
                                    <td>: {{ $no_whatsapp ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Sekolah</th>
                                    <td>: {{ $sekolah_nama ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Jurusan</th>
                                    <td>: {{ $jurusan ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Role</th>
                                    <td>: {{ $role_id == 1 ? 'Admin' : 'User' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status Akun</th>
                                    <td>: {{ $status_akun }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        <i class="ri-close-line me-1"></i>Tutup</button>
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
                        <i class="ri-delete-bin-line me-2 text-danger"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-error-warning-line text-warning" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Apakah Anda yakin?</h4>
                    <p class="mb-0">Data pengguna ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="ri-delete-bin-line me-1"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>