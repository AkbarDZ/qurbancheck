@extends('layouts.app')

@section('title', 'Dashboard - Sistem Qurban')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-home.css') }}">
@endpush

@section('content')
<div class="metro-grid">
    <!-- 0. Welcome Message & Clock Tile (4x1) -->
    <div class="metro-tile metro-tile-4x1 bg-metro-welcome">
        <div class="welcome-content">
            <div class="welcome-text">
                <p class="text-uppercase fw-bold text-light opacity-75 mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Sistem Manajemen Qurban</p>
                <h2 class="fw-bold mb-0 text-white" style="font-family: 'Segoe UI', Inter, sans-serif;">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-white-50 mb-0 mt-1" style="font-size: 0.85rem;">Masuk sebagai: <strong class="text-white">{{ Auth::user()->role === 'owner/admin' ? 'Admin' : 'Pekerja' }}</strong></p>
            </div>
            <div class="welcome-clock text-end text-white">
                <div class="clock-time fw-bold" id="liveClockTime" style="font-size: 2.2rem; font-family: monospace;">00:00:00</div>
                <div class="clock-date text-white-50 mt-1 small" id="liveClockDate">Hari ini</div>
            </div>
        </div>
    </div>

    <!-- 1. Sapi Karantina (1x1) -->
    <a href="{{ url('/kesehatan?karantina=1') }}" class="metro-tile metro-tile-1x1 bg-metro-crimson">
        <div class="tile-title">Karantina</div>
        <div class="tile-content">
            <h2 class="tile-value">{{ $karantinaCount }}</h2>
            <span class="tile-subtext">Sapi diisolasi</span>
        </div>
        <i class="bi bi-heart-pulse-fill tile-icon"></i>
    </a>

    <!-- 2. Peringatan Stok Pakan (2x1) -->
    @if($lowStockPakan)
        <a href="{{ route('logistik.index') }}" class="metro-tile metro-tile-2x1 bg-metro-orange">
            <div class="tile-title">Peringatan Stok Pakan</div>
            <div class="tile-content">
                <h2 class="tile-value fs-4 mb-1" style="font-weight: 700;">{{ $lowStockPakan->nama_pakan }} sisa {{ round($lowStockPakan->stok_kg) }} Kg!</h2>
                <span class="tile-subtext text-white">Stok kritis, segera lakukan pengisian</span>
            </div>
            <i class="bi bi-box-seam-fill tile-icon"></i>
        </a>
    @else
        <a href="{{ route('logistik.index') }}" class="metro-tile metro-tile-2x1 bg-metro-green">
            <div class="tile-title">Stok Pakan</div>
            <div class="tile-content">
                <h2 class="tile-value fs-4 mb-1" style="font-weight: 700;">Aman</h2>
                <span class="tile-subtext">Seluruh stok pakan di gudang memadai</span>
            </div>
            <i class="bi bi-check-circle-fill tile-icon"></i>
        </a>
    @endif

    <!-- 3. Belum Diperiksa (1x1) -->
    <a href="{{ route('syariat.index') }}" class="metro-tile metro-tile-1x1 bg-metro-blue">
        <div class="tile-title">Belum Diperiksa</div>
        <div class="tile-content">
            <h2 class="tile-value">{{ $belumDiperiksaCount }}</h2>
            <span class="tile-subtext">Belum screening</span>
        </div>
        <i class="bi bi-clipboard2-pulse tile-icon"></i>
    </a>

    <!-- 4. Total Populasi & Nilai Aset (2x2 untuk Admin, 2x1 untuk Pekerja) -->
    @if(Auth::user()->role === 'owner/admin')
        <a href="{{ route('ternak.index') }}" class="metro-tile metro-tile-2x2 bg-metro-purple">
            <div class="tile-title">Aset & Populasi</div>
            <div class="tile-content my-auto">
                <div class="mb-3">
                    <span class="tile-subtext d-block text-uppercase small opacity-75">Total Populasi</span>
                    <h2 class="tile-value fs-1" style="font-weight: 800;">{{ $totalPopulasi }} <span class="fs-5 fw-normal text-light-50">Ekor</span></h2>
                </div>
                <div>
                    <span class="tile-subtext d-block text-uppercase small opacity-75">Estimasi HPP Modal</span>
                    <h2 class="tile-value fs-2" style="font-weight: 800;">{{ $totalHppFormatted }}</h2>
                </div>
            </div>
            <i class="bi bi-cash-stack tile-icon"></i>
        </a>
    @else
        <a href="{{ route('ternak.index') }}" class="metro-tile metro-tile-2x1 bg-metro-purple">
            <div class="tile-title">Total Populasi</div>
            <div class="tile-content">
                <h2 class="tile-value fs-1 mb-1" style="font-weight: 800;">{{ $totalPopulasi }} Ekor</h2>
                <span class="tile-subtext">Jumlah populasi sapi saat ini</span>
            </div>
            <i class="bi bi-people tile-icon"></i>
        </a>
    @endif

    <!-- 5. Siap Jual / Layak Syariat (2x1) -->
    <a href="{{ route('syariat.index') }}" class="metro-tile metro-tile-2x1 bg-metro-green">
        <div class="tile-title">Siap Jual & Layak Syariat</div>
        <div class="tile-content">
            <h2 class="tile-value fs-3 mb-1" style="font-weight: 800;">{{ $layakSkkhCount }} Ekor</h2>
            <span class="tile-subtext">Layak Qurban & Ber-SKKH (Siap Pasarkan)</span>
        </div>
        <i class="bi bi-patch-check-fill tile-icon"></i>
    </a>

    <!-- 6. Beban Pakan Bulan Ini (2x1) - Hanya Owner/Admin -->
    @if(Auth::user()->role === 'owner/admin')
        <a href="{{ route('logistik.index') }}" class="metro-tile metro-tile-2x1 bg-metro-teal">
            <div class="tile-title">Biaya Pakan Bulan Ini</div>
            <div class="tile-content">
                <h2 class="tile-value fs-3 mb-1" style="font-weight: 800;">Rp {{ number_format($bebanPakanBulanIni, 0, ',', '.') }}</h2>
                <span class="tile-subtext">Total pengeluaran distribusi pakan</span>
            </div>
            <i class="bi bi-wallet2 tile-icon"></i>
        </a>
    @endif

    <!-- 7. Quick Action: + Distribusi Pakan (1x1) -->
    <a href="{{ route('logistik.index') }}" class="metro-tile metro-tile-1x1 bg-metro-dark">
        <div class="tile-title">Jalan Pintas</div>
        <div class="tile-content">
            <h2 class="tile-value fs-5 mb-1" style="font-weight: 700;">+ Pakan</h2>
            <span class="tile-subtext">Catat Distribusi</span>
        </div>
        <i class="bi bi-plus-square-fill tile-icon"></i>
    </a>

    <!-- 8. Quick Action: + Cek Kesehatan (1x1) -->
    <a href="{{ url('/kesehatan?action=tambah') }}" class="metro-tile metro-tile-1x1 bg-metro-dark">
        <div class="tile-title">Jalan Pintas</div>
        <div class="tile-content">
            <h2 class="tile-value fs-5 mb-1" style="font-weight: 700;">+ Kesehatan</h2>
            <span class="tile-subtext">Catat Rekam Medis</span>
        </div>
        <i class="bi bi-plus-circle-fill tile-icon"></i>
    </a>
</div>

@push('scripts')
<script>
    function updateLiveClock() {
        const clockTimeEl = document.getElementById('liveClockTime');
        const clockDateEl = document.getElementById('liveClockDate');
        if (!clockTimeEl || !clockDateEl) return;

        const now = new Date();
        
        // Format Waktu (HH:MM:SS)
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        clockTimeEl.innerText = `${hh}:${mm}:${ss}`;

        // Format Tanggal (Nama Hari, DD Nama Bulan YYYY)
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        clockDateEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    
    setInterval(updateLiveClock, 1000);
    updateLiveClock(); // Panggilan pertama
</script>
@endpush
@endsection