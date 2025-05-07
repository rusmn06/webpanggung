<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand & Divider tetap muncul untuk semua -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-text mx-3">SID Panggung</div>
    </a>
    <hr class="sidebar-divider my-0">

    <!-- Dashboard (semua) -->
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- MENU USER -->
    @if(auth()->user()->role === 'user')
        <div class="sidebar-heading">Menu Manajemen</div>
        <li class="nav-item {{ request()->is('tenagakerja*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Kuesioner Tenaga Kerja</span>
            </a>
        </li>
    @endif

    <!-- MENU ADMIN -->
    @if(auth()->user()->role === 'admin')

        <li class="nav-item {{ request()->is('tenagakerja*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Kuesioner Tenaga Kerja</span>
            </a>
        </li>
    
        <li class="nav-item {{ request()->is('admin/tenagakerja*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.tkw.index') }}">
                <i class="fas fa-fw fa-check-circle"></i>
                <span>Verifikasi Tenaga Kerja</span>
            </a>
        </li>

        {{-- Tambahkan item‐item admin lain di sini --}}
    @endif


    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>