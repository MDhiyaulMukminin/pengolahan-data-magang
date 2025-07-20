<div>
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
        <!-- Dark Logo-->
        @if(Auth::user()->role->nama === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="40">
                </span>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="40">
                </span>
            </a>
        @endif

        <!-- Light Logo-->
        @if(Auth::user()->role->nama === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="40">
                </span>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-pa-2.png') }}" alt="" height="40">
                </span>
            </a>
        @endif
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        {{-- <div class="dropdown sidebar-user m-1 rounded">
            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="text-start">
                        <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                        <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                                class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                                class="align-middle">Online</span></span>
                    </span>
                </span>
            </button>
        </div> --}}

        <div id="scrollbar">
            <div class="container-fluid">
                <div id="two-column-menu"></div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title">
                        <span data-key="t-menu">
                            Menu {{ Auth::user()->role->nama === 'admin' ? 'Admin' : 'User' }}
                        </span>
                    </li>

                    {{-- Dashboard - Available for both --}}
                    <li class="nav-item">
                        @if(Auth::user()->role->nama === 'admin')
                            <a class="nav-link menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">
                                <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                            </a>
                        @else
                            <a class="nav-link menu-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                               href="{{ route('user.dashboard') }}">
                                <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                            </a>
                        @endif
                    </li>

                    {{-- Admin Only Menu --}}
                    @if(Auth::user()->role->nama === 'admin')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.alumni-magang') ? 'active' : '' }}"
                               href="{{ route('admin.alumni-magang') }}">
                                <i class="ri-graduation-cap-line"></i><span>Data Alumni Magang</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.peserta-magang') ? 'active' : '' }}"
                               href="{{ route('admin.peserta-magang') }}">
                                <i class="ri-group-line"></i> <span>Data Peserta Magang</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.sekolah') ? 'active' : '' }}"
                               href="{{ route('admin.sekolah') }}">
                                <i class="ri-school-line"></i> <span>Data Sekolah</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.pengajuan') ? 'active' : '' }}"
                               href="{{ route('admin.pengajuan') }}">
                                <i class="ri-file-list-line"></i> <span>Pengajuan Magang</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"
                            href="{{ route('admin.laporan') }}">
                                <i class="ri-survey-line"></i> <span>Laporan</span>
                            </a>
                        </li>

                        <li class="menu-title"><i class="ri-more-fill"></i> <span>Pengaturan</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarAuth">
                                <i class="ri-account-circle-line"></i> <span>Autentikasi</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarAuth">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user') }}"
                                           class="nav-link {{ request()->routeIs('admin.user') ? 'active' : '' }}">
                                            Kelola User
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('logout') }}" class="nav-link">
                                            Keluar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    {{-- User Only Menu --}}
                    @if(Auth::user()->role->nama === 'user')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.profile') ? 'active' : '' }}"
                               href="{{ route('user.profile') }}">
                                <i class="ri-user-line"></i> <span>Profile</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.pengajuan') ? 'active' : '' }}"
                               href="{{ route('user.pengajuan') }}">
                                <i class="ri-file-add-line"></i> <span>Pengajuan Magang</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.alumni-magang') ? 'active' : '' }}"
                               href="{{ route('user.alumni-magang') }}">
                                <i class="ri-graduation-cap-line"></i> <span>Alumni Magang</span>
                            </a>
                        </li>

                        <li class="menu-title"><i class="ri-more-fill"></i> <span>Akun</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ route('logout') }}">
                                <i class="ri-logout-box-line"></i> <span>Keluar</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
</div>