<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manajemen Qurban')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
    @stack('styles')
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex vh-100 overflow-hidden"> @include('components.sidebar')

    <div class="main-content d-flex flex-column vh-100 overflow-y-auto">

        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>

        @include('components.footer')

    </div>

    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.storageBaseUrl = "{{ Storage::disk('s3')->url('_.txt') }}".replace('_.txt', '').replace(/\/$/, "");
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.getElementById('sidebarToggle');

            // Restore state from localStorage
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }

            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Auto-dismiss success alerts after 5 seconds
            setTimeout(function () {
                let successAlerts = document.querySelectorAll('.alert-success');
                successAlerts.forEach(function (alert) {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                });
            }, 5000);
        });

        // Global SweetAlert2 overrides
        window.alert = function(message) {
            Swal.fire({
                text: message,
                icon: 'info',
                confirmButtonColor: '#428475'
            });
        };

        // Automatic interceptor for delete confirm buttons
        document.addEventListener('click', function (e) {
            const target = e.target.closest('.btn-delete-confirm');
            if (target) {
                e.preventDefault();
                const form = target.closest('form');
                const message = target.getAttribute('data-message') || "Apakah Anda yakin ingin menghapus data ini?";

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed && form) {
                        form.submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
