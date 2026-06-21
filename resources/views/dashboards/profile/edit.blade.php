@extends('layouts.app')

@section('title', 'Edit Profil - Sistem Qurban')

@section('content')
<div class="card shadow border-1 mb-4">
    <div class="card-body">
        <h3 class="fw-bold mb-0 text-dark">Edit Profil</h3>
        <p class="text-muted mb-0">Perbarui data diri, alamat email, dan kata sandi akun Anda.</p>
    </div>
</div>

<div class="card shadow border-1">
    <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-fill-gear me-2 text-primary"></i>Data Akun Anda</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-semibold text-secondary">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold text-secondary">Alamat Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label fw-semibold text-secondary">Role Akses</label>
                    <input type="text" class="form-control bg-light" id="role" 
                           value="{{ $user->role === 'owner/admin' ? 'Admin' : 'Pekerja' }}" readonly disabled>
                    <div class="form-text text-muted" style="font-size: 0.75rem;">Role akses Anda dikelola oleh sistem dan tidak dapat diubah sendiri.</div>
                </div>
            </div>

            <hr class="my-4 text-muted">

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Ganti Password <span class="text-secondary fw-normal fs-6">(Opsional)</span></h5>
            <p class="text-muted small mb-4">Biarkan kolom password di bawah ini kosong jika Anda tidak ingin mengubah password akun Anda saat ini.</p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-semibold text-secondary">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                           placeholder="Minimal 6 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold text-secondary">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ url('/') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
