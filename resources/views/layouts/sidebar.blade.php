<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand & Divider -->
    @php
    $dashRoute = auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('dashboard');
    $dashActive = request()->is(auth()->user()->role==='admin' ? 'admin/dashboard*' : 'dashboard*');
    @endphp

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
    href="{{ $dashRoute }}">
    <div class="sidebar-brand-text mx-3">SID Panggung</div>
    </a>
    <hr class="sidebar-divider my-0">

    <!-- Dashboard-->
    <li class="nav-item {{ $dashActive ? 'active' : '' }}">
    <a class="nav-link" href="{{ $dashRoute }}">
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
    <div class="sidebar-heading">Menu Manajemen</div>
        <li class="nav-item {{ request()->is('tenagakerja*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Kuesioner Tenaga Kerja</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/user*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-table"></i>
                <span>Manajemen Akun</span>
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