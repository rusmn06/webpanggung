{{-- resources/views/admin/akun/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Akun User')

@push('styles')
    <!-- (Optional) custom CSS jika diperlukan -->
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Akun User</h1>
        <a href="{{ route('admin.user.create') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
           &laquo; Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input Akun Baru</h6>
        </div>
        <div class="card-body">
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- NAME -->
                <div class="col-lg-6 mb-3">
                    <label for="name">Nama Lengkap</label>
                    <input 
                        type="text"
                        name="name"
                        id="name"
                        value=""
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- USERNAME -->
                <div class="col-lg-6 mb-3">
                    <label for="username">Username</label>
                    <input 
                        type="text"
                        name="username"
                        id="username"
                        value=""
                        class="form-control @error('username') is-invalid @enderror"
                        placeholder="Masukkan username (unique)">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- PASSWORD -->
                <div class="col-lg-6 mb-3 position-relative">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input 
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 6 karakter">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- PASSWORD CONFIRMATION -->
                <div class="col-lg-6 mb-3 position-relative">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-group">
                        <input 
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Ketik ulang password">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('admin.user.index') }}" class="btn btn-light ml-2">
                    <i class="fas fa-times-circle"></i> Batal
                </a>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.getAttribute('data-target'));
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush
