@extends('layouts.main') {{-- atau layouts.app, tergantung structure --}}

@section('title', 'Settings Profil')

@push('styles')
    <!-- (Optional) CSS tambahan -->
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings Profil</h1>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow col-md-6 mb-4">
      <div class="card-body">
        <form action="{{ route('profile.settings.update') }}" method="POST">
          @csrf

          {{-- Nama --}}
          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input 
              type="text"
              name="name"
              id="name"
              class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', $user->name) }}">
            @error('name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          {{-- Username (read-only) --}}
          <div class="form-group">
            <label for="username">Username</label>
            <input 
              type="text"
              id="username"
              class="form-control"
              value="{{ $user->username }}"
              disabled>
          </div>

          {{-- Password Baru --}}
          <div class="form-group position-relative">
            <label for="password">Password Baru <small class="text-muted">(kosongkan jika tidak ingin ganti)</small></label>
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
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>
          </div>

          {{-- Konfirmasi Password --}}
          <div class="form-group position-relative">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-group">
              <input 
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Ketik ulang password baru">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              @error('password_confirmation')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-pencil"></i> Update
            </button>
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
