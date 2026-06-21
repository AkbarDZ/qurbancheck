<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - QurbanCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/auth-login.css') }}">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('image/icons/WhatsApp Image 2026-06-12 at 17.24.34.jpeg') }}" alt="Logo QurbanCheck" class="rounded mb-3 shadow" style="width: 64px; height: 64px; object-fit: cover;">
            <h3 class="brand-name mb-1">QurbanCheck</h3>
            <p class="text-muted small">Worker & Admin Portal</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 small py-2" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3 small py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ url('/login') }}" method="POST" id="formLogin">
            @csrf
            
            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold text-muted">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" 
                           placeholder="nama@qurban.com" required autofocus autocomplete="email">
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label small fw-bold text-muted mb-0">Password</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    <span class="input-group-text toggle-password" id="btnTogglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" style="cursor: pointer;" for="remember">Ingat Saya</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-login w-100 fw-bold" id="btnLoginSubmit">
                <span class="spinner-border spinner-border-sm d-none me-2" id="loadingIcon" role="status" aria-hidden="true"></span>
                Masuk ke Dashboard
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('btnTogglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const formLogin = document.getElementById('formLogin');
        const btnSubmit = document.getElementById('btnLoginSubmit');
        const loadingIcon = document.getElementById('loadingIcon');

        // Toggle password visibility
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            if (type === 'text') {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        });

        // Add loading state to button on submit
        formLogin.addEventListener('submit', function () {
            btnSubmit.disabled = true;
            loadingIcon.classList.remove('d-none');
        });
    });
</script>
</body>
</html>
