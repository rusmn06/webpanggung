{{-- resources/views/profile/show.blade.php --}}
@php
  use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.main')

@section('title', 'Profil Saya')

@push('styles')
<style>
.profile-avatar { position: relative; display: inline-block; }
.profile-avatar img {
    width:150px; height:150px; object-fit:cover; border-radius:50%;
    cursor: pointer;
}
.profile-avatar .overlay {
    position: absolute; bottom:0; right:0;
    background:rgba(0,0,0,0.6); border-radius:50%;
    padding:6px; color:#fff;
}
input[type="file"] { display: none; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow mb-4">
    <div class="card-body text-center">
      {{-- Inline Avatar Upload Form --}}
      <form id="avatarForm" action="{{ route('profile.avatar.update') }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        <div class="profile-avatar" onclick="document.getElementById('avatarInput').click()">
          <img src="{{ auth()->user()->avatar
                       ? asset('storage/avatars/' . auth()->user()->avatar)
                       : asset('template/img/undraw_profile.svg') }}"
               alt="Avatar">
          <div class="overlay" onclick="document.getElementById('avatarInput').click()"><i class="fas fa-camera"></i></div>
        </div>
        <input type="file" name="avatar" id="avatarInput" accept="image/*">
      </form>

      {{-- User Info --}}
      <table class="table table-borderless text-left mx-auto" style="max-width:400px;">
        <tbody>
          <tr><th>Nama</th>          <td>{{ auth()->user()->name }}</td></tr>
          <tr><th>Username</th>      <td>{{ auth()->user()->username }}</td></tr>
          <tr><th>Role</th>          <td>{{ ucfirst(auth()->user()->role) }}</td></tr>
          <tr><th>Terakhir Update</th>
              <td>{{ auth()->user()->updated_at->format('d F Y, H:i') }}</td></tr>
        </tbody>
      </table>

      {{-- Button ke Settings --}}
      <a href="{{ route('profile.settings') }}" class="btn btn-sm btn-primary mt-3">
        <i class="fas fa-cog fa-sm fa-fw mr-2"></i> Settings
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // begitu file dipilih, langsung submit
  document.getElementById('avatarInput')
    .addEventListener('change', () => document.getElementById('avatarForm').submit());
</script>
@endpush
