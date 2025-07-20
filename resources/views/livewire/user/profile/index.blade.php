<div class="container-fluid px-4 py-6">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h3 mb-2 text-gray-800">Profile Saya</h1>
                <p class="text-muted">Kelola informasi profil dan keamanan akun Anda</p>

                @if ($user->status_akun === 'belum lengkapi profile')
                <div class="alert alert-warning d-flex align-items-center mt-3" role="alert">
                    <i class="ri-error-warning-line me-2"></i>
                    <div>
                        <strong>Profil Belum Lengkap!</strong>
                        Silakan lengkapi data profil Anda untuk mengaktifkan akun.
                    </div>
                </div>
                @elseif ($user->status_akun === 'aktif' && $user->sekolah && $user->sekolah->status !== 'aktif')
                    <div class="alert alert-info d-flex align-items-center mt-3" role="alert">
                        <i class="ri-information-line me-2"></i>
                        <div>
                            <strong>Menunggu Verifikasi!</strong>
                            Profil Anda sudah lengkap dan sedang menunggu verifikasi sekolah oleh admin.
                        </div>
                    </div>
                @endif

            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-user-line me-2"></i>
                                Informasi Profile
                            </h5>
                        </div>

                        <div class="card-body">
                            @if ($isEditingProfile)
                                <!-- Edit Profile Form -->
                                <form wire:submit.prevent="updateProfile">
                                    <div class="row g-3">
                                        <!-- Nama -->
                                        <div class="col-12">
                                            <label for="nama" class="form-label">Nama Lengkap</label>
                                            <input type="text"
                                                   id="nama"
                                                   wire:model="nama"
                                                   class="form-control @error('nama') is-invalid @enderror">
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Sekolah with Add New Option -->
                                        <div class="col-12">
                                            <label for="sekolah_id" class="form-label">Sekolah</label>

                                            @if (!$isAddingSchool)
                                                <div class="d-flex gap-2 align-items-start">
                                                    <div class="flex-grow-1">
                                                        <select id="sekolah_id"
                                                                wire:model="sekolah_id"
                                                                class="form-select @error('sekolah_id') is-invalid @enderror">
                                                            <option value="">-- Pilih Sekolah --</option>

                                                            <!-- Tampilkan sekolah aktif -->
                                                            @foreach($sekolahs as $sekolah)
                                                                <option value="{{ $sekolah->id }}">
                                                                    {{ $sekolah->nama }}
                                                                </option>
                                                            @endforeach

                                                            <!-- Tampilkan sekolah user jika statusnya menunggu -->
                                                            @if($userSekolah && $userSekolah->status === 'menunggu' && !$sekolahs->contains('id', $userSekolah->id))
                                                                <option value="{{ $userSekolah->id }}" class="text-warning">
                                                                    {{ $userSekolah->nama }} (Menunggu Verifikasi)
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('sekolah_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror

                                                        <!-- Info status sekolah yang dipilih -->
                                                        @if($userSekolah && $userSekolah->status === 'menunggu' && $sekolah_id == $userSekolah->id)
                                                            <small class="text-warning mt-1 d-block">
                                                                <i class="ri-time-line me-1"></i>
                                                                Sekolah ini sedang menunggu verifikasi admin
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <button type="button"
                                                            wire:click="toggleAddSchool"
                                                            class="btn btn-outline-primary btn-sm flex-shrink-0"
                                                            title="Tambah Sekolah Baru">
                                                        <i class="ri-add-line"></i>
                                                        Tambah
                                                    </button>
                                                </div>

                                                <small class="text-muted">
                                                    Tidak menemukan sekolah Anda? <button type="button" wire:click="toggleAddSchool" class="btn btn-link p-0 text-decoration-underline">Klik disini untuk menambahkan</button>
                                                </small>
                                            @else
                                                <!-- Form Add New School -->
                                                <div class="border rounded p-3 bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0">
                                                            <i class="ri-school-line me-1"></i>
                                                            Tambah Sekolah Baru
                                                        </h6>
                                                        <button type="button"
                                                                wire:click="toggleAddSchool"
                                                                class="btn btn-sm btn-outline-secondary">
                                                            <i class="ri-close-line"></i>
                                                        </button>
                                                    </div>

                                                    <div class="alert alert-info py-2 mb-3">
                                                        <small>
                                                            <i class="ri-information-line me-1"></i>
                                                            Sekolah yang Anda tambahkan akan diverifikasi oleh admin terlebih dahulu.
                                                        </small>
                                                    </div>

                                                    <div class="row g-2">
                                                        <div class="col-12">
                                                            <label for="nama_sekolah_baru" class="form-label">Nama Sekolah</label>
                                                            <input type="text"
                                                                   id="nama_sekolah_baru"
                                                                   wire:model="nama_sekolah_baru"
                                                                   class="form-control @error('nama_sekolah_baru') is-invalid @enderror"
                                                                   placeholder="Masukkan nama sekolah">
                                                            @error('nama_sekolah_baru')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex gap-2 justify-content-end">
                                                                <button type="button"
                                                                        wire:click="toggleAddSchool"
                                                                        class="btn btn-sm btn-secondary">
                                                                    Batal
                                                                </button>
                                                                <button type="button"
                                                                        wire:click="addNewSchool"
                                                                        class="btn btn-sm btn-success">
                                                                    <i class="ri-add-line me-1"></i>
                                                                    Tambah Sekolah
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- No Induk & Jurusan -->
                                        <div class="col-md-6">
                                            <label for="no_induk" class="form-label">Nomor Induk</label>
                                            <input type="text"
                                                   id="no_induk"
                                                   wire:model="no_induk"
                                                   class="form-control @error('no_induk') is-invalid @enderror">
                                            @error('no_induk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="jurusan" class="form-label">Jurusan</label>
                                            <input type="text"
                                                   id="jurusan"
                                                   wire:model="jurusan"
                                                   class="form-control @error('jurusan') is-invalid @enderror">
                                            @error('jurusan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- No WhatsApp -->
                                        <div class="col-12">
                                            <label for="no_whatsapp" class="form-label">Nomor WhatsApp</label>
                                            <input type="text"
                                                   id="no_whatsapp"
                                                   wire:model="no_whatsapp"
                                                   class="form-control @error('no_whatsapp') is-invalid @enderror"
                                                   placeholder="08xxxxxxxxxx">
                                            @error('no_whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button"
                                                wire:click="toggleEditProfile"
                                                class="btn btn-secondary">
                                            <i class="ri-close-line me-1"></i>
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i>
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- Display Profile Info -->
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Nama Lengkap</label>
                                            <p class="fw-bold mb-0">{{ $user->nama ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Email</label>
                                            <p class="fw-bold mb-0">{{ $user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Sekolah</label>
                                            <div>
                                                <p class="fw-bold mb-0">{{ $user->sekolah->nama ?? '-' }}</p>
                                                @if($user->sekolah && $user->sekolah->status === 'menunggu')
                                                    <small class="text-warning">
                                                        <i class="ri-time-line me-1"></i>
                                                        Menunggu verifikasi admin
                                                    </small>
                                                @elseif($user->sekolah && $user->sekolah->status === 'aktif')
                                                    <small class="text-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        Terverifikasi
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Nomor Induk</label>
                                            <p class="fw-bold mb-0">{{ $user->no_induk ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Jurusan</label>
                                            <p class="fw-bold mb-0">{{ $user->jurusan ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Nomor WhatsApp</label>
                                            <p class="fw-bold mb-0">{{ $user->no_whatsapp ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button wire:click="toggleEditProfile" class="btn btn-primary">
                                        <i class="ri-edit-line me-1"></i>
                                        Edit Profile
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-12 col-lg-4">
                    <!-- Security Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-danger">
                            <h6 class="card-title mb-0 text-white">
                                <i class="ri-lock-line me-2"></i>
                                Keamanan
                            </h6>
                        </div>

                        <div class="card-body">
                            @if ($isEditingPassword)
                                <!-- Change Password Form -->
                                <form wire:submit.prevent="updatePassword">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password"
                                               id="current_password"
                                               wire:model="current_password"
                                               class="form-control @error('current_password') is-invalid @enderror">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <input type="password"
                                               id="new_password"
                                               wire:model="new_password"
                                               class="form-control @error('new_password') is-invalid @enderror">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password"
                                               id="new_password_confirmation"
                                               wire:model="new_password_confirmation"
                                               class="form-control">
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ri-key-line me-1"></i>
                                            Ubah Password
                                        </button>
                                        <button type="button"
                                                wire:click="toggleEditPassword"
                                                class="btn btn-outline-secondary">
                                            <i class="ri-close-line me-1"></i>
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- Password Info -->
                                <div class="text-center py-3">
                                    <div class="mb-3">
                                        <i class="ri-lock-line ri-2x text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6 class="mb-2">Password</h6>
                                    <p class="text-muted small mb-3">
                                        Terakhir diubah: {{ $user->updated_at->format('d M Y') }}
                                    </p>
                                    <button wire:click="toggleEditPassword" class="btn btn-danger">
                                        <i class="ri-key-line me-1"></i>
                                        Ubah Password
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Info Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-success">
                            <h6 class="card-title mb-0 text-white">
                                <i class="ri-information-line me-2"></i>
                                Info Akun
                            </h6>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Status Akun</label>
                                <div>
                                    @if($user->status_akun === 'belum lengkapi profile')
                                        <span class="badge bg-warning text-dark">
                                            <i class="ri-error-warning-line me-1"></i>
                                            {{ $user->status_akun }}
                                        </span>
                                    @elseif($user->status_akun === 'aktif')
                                        <span class="badge bg-success">
                                            <i class="ri-check-line me-1"></i>
                                            {{ $user->status_akun }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($user->status_akun ?? 'Tidak Diketahui') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Role</label>
                                <p class="fw-bold mb-0">{{ $user->role->nama ?? 'User' }}</p>
                            </div>
                            <div class="mb-0">
                                <label class="form-label text-muted small">Bergabung</label>
                                <p class="fw-bold mb-0">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>