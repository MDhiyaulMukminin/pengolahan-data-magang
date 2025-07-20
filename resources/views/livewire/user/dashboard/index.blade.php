<div>
    <!-- Header Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="ri-dashboard-3-line ri-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1">Selamat Datang, {{ auth()->user()->nama }}</h3>
                                    <p class="mb-0">Dashboard Peserta Magang</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="text-white-50">
                                <i class="ri-calendar-line me-1"></i>
                                {{ \Carbon\Carbon::now()->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="ri-information-line text-primary ri-lg"></i>
                        </div>
                        <h5 class="mb-0 text-dark">Selamat Datang di Aplikasi Pengolahan Data Peserta Magang</h5>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <p class="text-muted mb-0 lh-lg">
                        Aplikasi ini digunakan untuk mempermudah proses pendaftaran dan pengelolaan data peserta magang di <strong>Pengadilan Agama Palembang</strong>. Melalui sistem ini, kamu dapat melakukan pendaftaran magang secara online, memantau status pengajuan, serta melihat data alumni yang pernah magang di sini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Guide Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="ri-guide-line text-success ri-lg"></i>
                        </div>
                        <h5 class="mb-0 text-white">Panduan Penggunaan Aplikasi</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Step 1 -->
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">1</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 text-dark">
                                        <i class="ri-user-settings-line me-2 text-primary"></i>
                                        Lengkapi Profil
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        Sebelum mengajukan permohonan magang, silakan lengkapi data diri kamu terlebih dahulu di menu <strong>Profil</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">2</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 text-dark">
                                        <i class="ri-file-add-line me-2 text-info"></i>
                                        Ajukan Magang
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        Setelah profil terisi, lakukan pendaftaran magang melalui menu <strong>Pengajuan</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">3</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 text-dark">
                                        <i class="ri-time-line me-2 text-warning"></i>
                                        Menunggu Verifikasi
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        Pengajuan kamu akan diproses oleh admin. Pastikan kamu memantau statusnya secara berkala.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">4</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-2 text-dark">
                                        <i class="ri-group-line me-2 text-success"></i>
                                        Lihat Data Alumni
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        Kamu juga dapat melihat daftar alumni yang pernah mengikuti magang di Pengadilan Agama Palembang melalui menu <strong>Alumni Magang</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-dark">
                <i class="ri-flashlight-line me-2"></i>
                Aksi Cepat
            </h5>
        </div>
        
        <!-- Profile Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="ri-user-settings-line ri-2x text-primary"></i>
                    </div>
                    <h6 class="mb-2 text-dark">Kelola Profil</h6>
                    <p class="text-muted small mb-3">Lengkapi dan perbarui data diri kamu</p>
                    <a href="{{ route('user.profile') }}" class="btn btn-primary btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>
                        Buka Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengajuan Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="ri-file-add-line ri-2x text-info"></i>
                    </div>
                    <h6 class="mb-2 text-dark">Pengajuan Magang</h6>
                    <p class="text-muted small mb-3">Ajukan permohonan magang baru</p>
                    <a href="{{ route('user.pengajuan') }}" class="btn btn-info btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>
                        Lihat Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body text-center p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="ri-time-line ri-2x text-warning"></i>
                    </div>
                    <h6 class="mb-2 text-dark">Status Pengajuan</h6>
                    <p class="text-muted small mb-3">Pantau status pengajuan kamu</p>
                    <a href="{{ route('user.pengajuan') }}" class="btn btn-warning btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>
                        Cek Status
                    </a>
                </div>
            </div>
        </div>

        <!-- Alumni Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="ri-group-line ri-2x text-success"></i>
                    </div>
                    <h6 class="mb-2 text-dark">Alumni Magang</h6>
                    <p class="text-muted small mb-3">Lihat daftar alumni magang</p>
                    <a href="{{ route('user.alumni-magang') }}" class="btn btn-success btn-sm">
                        <i class="ri-arrow-right-line me-1"></i>
                        Lihat Alumni
                    </a>
                </div>
            </div>
        </div>
    </div>
