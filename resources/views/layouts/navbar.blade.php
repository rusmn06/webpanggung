<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Search -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle"
            href="#"
            id="userDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
                {{-- Dynamic user name --}}
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ auth()->user()->name }}
                </span>
                {{-- Dynamic avatar (fallback to default) --}}
                <img class="img-profile rounded-circle"
                    src="{{ auth()->user()->avatar 
                            ? asset('storage/avatars/' . auth()->user()->avatar) 
                            : asset('template/img/undraw_profile.svg') }}">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                {{-- Profile --}}
                <a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                {{-- Settings --}}
                <a class="dropdown-item" href="{{ route('profile.settings') }}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                {{-- Logout --}}
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yakin ingin logout?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" di bawah jika kamu yakin ingin keluar dari sesi ini.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
