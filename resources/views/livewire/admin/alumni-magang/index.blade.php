<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms="dismissAlert">
            {{ session('message') }}
            <button type="button" class="btn-close" wire:click="dismissAlert" aria-label="Close"></button>
        </div>
    @endif

    <h4 class="mb-3">Daftar Alumni Magang</h4>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-primary" wire:click="openModal">
            <i class="ri-add-line me-1"></i>Tambah
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
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Sekolah</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alumniMagang as $index => $alumni)
                    <tr class="text-center">
                        <th>{{ $alumniMagang->firstItem() + $index }}</th>
                        <td class="text-start">{{ $alumni->nama_alumni }}</td>
                        <td class="text-start">{{ $alumni->jurusan }}</td>
                        <td class="text-start">{{ $alumni->nama_sekolah }}</td>
                        <td>{{ \Carbon\Carbon::parse($alumni->tgl_mulai)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($alumni->tgl_selesai)->format('d-m-Y') }}</td>
                        <td class="text-start">{{ $alumni->keterangan ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" wire:click="openEditModal({{ $alumni->id }})">
                                <i class="ri-edit-2-line me-1"></i>Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $alumni->id }})">
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
        {{ $alumniMagang->links() }}
    </div>

    <!-- Modal Form -->
    @if($isOpen)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-{{ $isEdit ? 'edit' : 'add' }}-line me-2"></i>
                        {{ $isEdit ? 'Edit Data Alumni Magang' : 'Tambah Data Alumni Magang' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <div class="btn-group w-100" role="group" aria-label="Mode Input">
                                <button type="button" class="btn {{ !$manualInput ? 'btn-primary' : 'btn-outline-primary' }}" 
                                        wire:click="disableManualInput">
                                    <i class="ri-user-line me-1"></i>Pilih dari Peserta
                                </button>
                                <button type="button" class="btn {{ $manualInput ? 'btn-primary' : 'btn-outline-primary' }}" 
                                        wire:click="enableManualInput">
                                    <i class="ri-keyboard-line me-1"></i>Input Manual
                                </button>
                            </div>
                        </div>

                        @if(session()->has('debug'))
                            <div class="alert alert-info small">
                                <i class="ri-information-line me-1"></i>{{ session('debug') }}
                            </div>
                        @endif

                        @if(!$manualInput)
                        <div class="mb-3">
                            <label for="peserta_magang_id" class="form-label">
                                <i class="ri-user-search-line me-1"></i>Pilih Peserta
                            </label>
                            <select class="form-select @error('peserta_magang_id') is-invalid @enderror" 
                                    id="peserta_magang_id" 
                                    wire:model="peserta_magang_id" 
                                    wire:change="selectPeserta">
                                <option value="">Pilih Peserta</option>
                                @foreach($this->pesertaMagangList as $peserta)
                                    @if($peserta->pengajuan && $peserta->pengajuan->user && $peserta->pengajuan->user->sekolah)
                                        <option value="{{ $peserta->id }}">
                                            {{ $peserta->pengajuan->user->nama }} - 
                                            {{ $peserta->pengajuan->user->sekolah->nama }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('peserta_magang_id') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>
                        @endif                    

                        <div class="mb-3">
                            <label for="nama_alumni" class="form-label">
                                <i class="ri-user-3-line me-1"></i>Nama Alumni
                            </label>
                            <input type="text" class="form-control @error('nama_alumni') is-invalid @enderror" id="nama_alumni" 
                                wire:model.live="nama_alumni" {{ !$manualInput ? 'readonly' : '' }}>
                            @error('nama_alumni') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jurusan" class="form-label">
                                <i class="ri-book-line me-1"></i>Jurusan
                            </label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" 
                                wire:model="jurusan" {{ !$manualInput ? 'readonly' : '' }}>
                            @error('jurusan') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <!-- Searchable Dropdown untuk Sekolah (mengikuti pola dari User Management) -->
                        @if($manualInput)
                        <div class="mb-3 position-relative">
                            <label for="sekolah_nama" class="form-label">
                                <i class="ri-school-line me-1"></i>Sekolah
                            </label>
                            
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

                            @if($showSekolahDropdown && count($filteredSekolah) > 0)
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

                            @if($showSekolahDropdown && strlen($sekolah_nama) >= 2 && count($filteredSekolah) === 0)
                                <ul class="list-group position-absolute w-100 shadow-lg border" style="z-index: 1050; top: 100%;">
                                    <li class="list-group-item text-muted text-center" style="padding: 8px 12px;">
                                        Sekolah tidak ditemukan
                                    </li>
                                </ul>
                            @endif

                            <!-- Hidden input untuk menyimpan ID sekolah -->
                            <input type="hidden" wire:model="sekolah_id">
                        </div>
                        @else
                        <div class="mb-3">
                            <label for="nama_sekolah" class="form-label">
                                <i class="ri-school-line me-1"></i>Sekolah
                            </label>
                            <input type="text" class="form-control @error('nama_sekolah') is-invalid @enderror" id="nama_sekolah" 
                                wire:model="nama_sekolah" readonly>
                            @error('nama_sekolah') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="tgl_mulai" class="form-label">
                                <i class="ri-calendar-check-line me-1"></i>Tanggal Mulai
                            </label>
                            <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" id="tgl_mulai" wire:model="tgl_mulai">
                            @error('tgl_mulai') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_selesai" class="form-label">
                                <i class="ri-calendar-close-line me-1"></i>Tanggal Selesai
                            </label>
                            <input type="date" class="form-control @error('tgl_selesai') is-invalid @enderror" id="tgl_selesai" wire:model="tgl_selesai">
                            @error('tgl_selesai') 
                                <span class="text-danger">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </span> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">
                                <i class="ri-file-text-line me-1"></i>Keterangan/Status
                            </label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" wire:model="keterangan" placeholder="Manual / Peserta Terdaftar / dll">
                            @error('keterangan') 
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
                    <button type="button" class="btn btn-primary" wire:click="store">
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
                    <p class="mb-0">Data alumni magang ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
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