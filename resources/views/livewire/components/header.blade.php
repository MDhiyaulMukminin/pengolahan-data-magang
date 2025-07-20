<div>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">

                    <button type="button"
                        class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none"
                        id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown d-md-none topbar-head-dropdown header-item"></div>

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button"
                            class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>

                    @php
                        use Illuminate\Support\Facades\Auth;
                        $user = Auth::user();
                    @endphp

                    <div class="d-flex align-items-center ms-sm-3">
                        <div class="text-start ms-2">
                            <span class="d-block fw-medium user-name-text">{{ $user->nama }}</span>
                            <span class="d-block fs-12 user-name-sub-text text-muted">{{ $user->role->nama }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>