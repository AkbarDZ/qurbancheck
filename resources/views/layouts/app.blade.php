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
    
    
    <style>
        /* Styling minimal untuk mengatur layout Flexbox */
        body {
            background-color: #f4f6f9; /* Warna latar belakang abu-abu terang */
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #0a3a2a; /* Dark theme untuk sidebar */
            transition: width 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            border-radius: 0.375rem;
            margin-bottom: 0.2rem;
            white-space: nowrap;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #5aa17f; /* Warna primary Bootstrap */
        }
        .main-content {
            width: calc(100% - 260px); /* Sisa ruang setelah sidebar */
            transition: width 0.3s ease;
            background: linear-gradient(to bottom, #f4f6f9 75%, #5aa17f 75%);
        }

        /* Collapsed sidebar */
        .sidebar.collapsed {
            width: 70px;
            padding: 1rem 0.5rem !important;
        }
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }
        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 0.5rem;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0 !important;
            font-size: 1.2rem;
        }
        .sidebar.collapsed .dropdown-toggle {
            justify-content: center;
        }
        .sidebar.collapsed .dropdown-toggle i {
            margin-right: 0 !important;
        }
        .main-content.expanded {
            width: calc(100% - 70px);
        }
    </style>
    @stack('styles')
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
        });
    </script>
    @stack('scripts')
</body>
</html>