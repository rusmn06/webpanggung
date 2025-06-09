@php
// Penentuan route dashboard berdasarkan role
$dashRoute = auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('dashboard');

// Penentuan kondisi aktif untuk dashboard
$dashActive = (auth()->user()->role === 'admin' && request()->routeIs('admin.dashboard')) ||
              (auth()->user()->role !== 'admin' && request()->routeIs('dashboard'));
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ $dashRoute }}">
        <div class="sidebar-brand-text mx-3">SID Panggung</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ $dashActive ? 'active' : '' }}">
        <a class="nav-link" href="{{ $dashRoute }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    @if(auth()->user()->role === 'user')
        <div class="sidebar-heading">
            Menu Manajemen
        </div>

        <li class="nav-item {{ request()->routeIs('tenagakerja.index') || request()->routeIs('tenagakerja.show') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Kuesioner Tenaga Kerja</span>
            </a>
        </li>
    @endif

    @if(auth()->user()->role === 'admin')
        <div class="sidebar-heading">
            Menu Admin
        </div>

        <li class="nav-item {{ request()->routeIs('tenagakerja.index') || request()->routeIs('tenagakerja.show') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-book-reader"></i>
                <span>Data Kuesioner</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Manajemen Akun</span>
            </a>
        </li>
    
        <li class="nav-item {{ request()->routeIs('admin.tkw.index') || request()->routeIs('admin.tkw.show') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.tkw.index') }}">
                <i class="fas fa-fw fa-user-check"></i>
                <span>Verifikasi Tenaga Kerja</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.tkw.listrt') || request()->routeIs('admin.tkw.showrt') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.tkw.listrt') }}">
                <i class="fas fa-fw fa-list-alt"></i>
                <span>Lihat Data per RT</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>