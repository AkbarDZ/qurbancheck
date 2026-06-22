<div id="sidebar" class="sidebar d-flex flex-column flex-shrink-0 p-3 sticky-top">
    <a href="{{ url('/') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2 sidebar-brand">
        <img src="{{ asset('image/icons/WhatsApp Image 2026-06-12 at 17.24.34.jpeg') }}" alt="Logo QurbanCheck" width="32" height="32" class="me-3 rounded">
        <span class="fs-5 fw-bold sidebar-text">QurbanCheck</span>
    </a>
    <hr class="text-secondary">
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" title="Dashboard">
                <i class="fa-solid fa-house me-2"></i><span class="sidebar-text">Beranda</span>
            </a>
        </li>
        <li>
            <a href="{{ route('ternak.index') }}" class="nav-link {{ request()->is('ternak*') ? 'active' : '' }}" title="Manajemen Ternak">
                <i class="fa-solid fa-cow me-2"></i><span class="sidebar-text">Manajemen Ternak</span>
            </a>
        </li>
        <li>
            <a href="{{ route('kesehatan.index') }}" class="nav-link {{ request()->is('kesehatan*') ? 'active' : '' }}" title="Kesehatan">
                <i class="fa-solid fa-heart-pulse me-2"></i><span class="sidebar-text">Kesehatan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('syariat.index') }}" class="nav-link {{ request()->is('syariat*') ? 'active' : '' }}" title="Pemeriksaan Syariat">
                <i class="fa-solid fa-clipboard me-2"></i><span class="sidebar-text">Kelayakan Kurban</span>
            </a>
        </li>
        <li>
            <a href="{{ route('logistik.index') }}" class="nav-link {{ request()->is('logistik*') ? 'active' : '' }}" title="Logistik Pakan">
                <i class="fa-solid fa-box-open me-2"></i><span class="sidebar-text">Logistik Pakan</span>
            </a>
        </li>
        
        @if(Auth::user() && Auth::user()->role === 'owner/admin')
        <hr class="text-secondary">
        <small class=" px-3 pb-2 text-uppercase text-light fw-bold sidebar-text" style="font-size: 0.75rem;">Master & Pengaturan</small>
        
        <li>
            <a href="{{ route('master.index') }}" class="nav-link {{ request()->is('master*') ? 'active' : '' }}" title="Master Data">
                <i class="fa-solid fa-database me-2"></i><span class="sidebar-text">Data Utama</span>
            </a>
        </li>
        <li>
            <a href="{{ route('pengguna.index') }}" class="nav-link {{ request()->is('pengguna*') ? 'active' : '' }}" title="Pengguna">
                <i class="fa-solid fa-user me-2"></i><span class="sidebar-text">Pengguna</span>
            </a>
        </li>
        @endif
        
        <hr class="text-secondary">

        <li>
            <a href="#" class="nav-link" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fa-solid fa-bars me-2"></i><span class="sidebar-text">Tutup Menu</span>
            </a>
        </li>

        <!-- User dropdown for mobile / small screens -->
        <li class="nav-item d-md-none mt-2">
            <hr class="text-secondary mb-2">
            <div class="dropdown dropup px-2">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUserMobile" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <strong class="sidebar-text">{{ Auth::user() ? Auth::user()->name : 'Tamu' }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUserMobile">
                    <li><a class="dropdown-item {{ request()->is('profil*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            Keluar
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </li>
    </ul>

    <!-- Bottom user dropdown for desktop / larger screens -->
    <hr class="text-secondary d-none d-md-block">
    <div class="dropdown dropup d-none d-md-block">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <strong class="sidebar-text">{{ Auth::user() ? Auth::user()->name : 'Tamu' }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item {{ request()->is('profil*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>