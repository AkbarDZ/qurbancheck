@extends('layouts.app')

@section('title', 'Dashboard - Sistem Qurban')

@push('styles')
<style>
    /* Reset styles for Metro UI */
    .metro-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: 150px;
        gap: 15px;
        margin-bottom: 30px;
    }

    .metro-tile {
        position: relative;
        color: #ffffff;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        transition: transform 0.15s cubic-bezier(0.1, 0.9, 0.2, 1), filter 0.15s ease;
        cursor: pointer;
        overflow: hidden;
        /* Windows 8 Metro Style: Sudut tajam 90 derajat, flat design tanpa border-radius / shadow */
        border: none !important;
        border-radius: 0px !important;
        box-shadow: none !important;
    }

    .metro-tile:hover {
        transform: scale(0.97);
        filter: brightness(90%);
        color: #ffffff;
    }

    .metro-tile:active {
        transform: scale(0.94);
    }

    .metro-tile-1x1 {
        grid-column: span 1;
        grid-row: span 1;
    }

    .metro-tile-2x1 {
        grid-column: span 2;
        grid-row: span 1;
    }

    .metro-tile-2x2 {
        grid-column: span 2;
        grid-row: span 2;
    }

    .metro-tile .tile-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 0;
        opacity: 0.85;
    }

    .metro-tile .tile-value {
        font-size: 2.25rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
    }

    .metro-tile .tile-subtext {
        font-size: 0.8rem;
        opacity: 0.8;
        display: block;
        margin-top: 4px;
    }

    .metro-tile .tile-icon {
        position: absolute;
        right: 10px;
        bottom: 5px;
        font-size: 4.5rem;
        opacity: 0.22;
        pointer-events: none;
        line-height: 1;
        transition: transform 0.25s ease;
    }

    .metro-tile:hover .tile-icon {
        transform: translate(-3px, -3px) scale(1.05);
    }

    .metro-tile-2x2 .tile-icon {
        font-size: 7.5rem;
        right: 15px;
        bottom: 10px;
    }

    /* Metro Flat Colors */
    .bg-metro-crimson { background-color: #b91d47 !important; }
    .bg-metro-orange { background-color: #da532c !important; }
    .bg-metro-green { background-color: #00a300 !important; }
    .bg-metro-blue { background-color: #2d89ef !important; }
    .bg-metro-teal { background-color: #00aba9 !important; }
    .bg-metro-purple { background-color: #603cba !important; }
    .bg-metro-dark { background-color: #1d1d1d !important; }

    .text-warning-light {
        color: #ffc107 !important;
    }

    /* Responsive grid columns */
    @media (max-width: 992px) {
        .metro-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 576px) {
        .metro-grid {
            grid-template-columns: 1fr;
            grid-auto-rows: auto;
        }
        .metro-tile-2x1, .metro-tile-2x2 {
            grid-column: span 1;
            grid-row: span 1;
        }
        .metro-tile {
            min-height: 140px;
        }
    }
</style>
@endpush

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-body">
        <h3 class="mb-0 text-dark fw-bold" style="font-family: 'Segoe UI', Inter, sans-serif;">Beranda Peternakan</h3>
        <p class="text-muted mb-0">Pemantauan operasional, finansial, dan kesiapan qurban secara real-time.</p>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body p-4">
        <div class="metro-grid">
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
                        <span class="tile-subtext text-warning-light">Stok kritis, segera lakukan pengisian</span>
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

            <!-- 4. Total Populasi & Nilai Aset (2x2) -->
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

            <!-- 5. Siap Jual / Layak Syariat (2x1) -->
            <a href="{{ route('syariat.index') }}" class="metro-tile metro-tile-2x1 bg-metro-green">
                <div class="tile-title">Siap Jual & Layak Syariat</div>
                <div class="tile-content">
                    <h2 class="tile-value fs-3 mb-1" style="font-weight: 800;">{{ $layakSkkhCount }} Ekor</h2>
                    <span class="tile-subtext">Layak Qurban & Ber-SKKH (Siap Pasarkan)</span>
                </div>
                <i class="bi bi-patch-check-fill tile-icon"></i>
            </a>

            <!-- 6. Beban Pakan Bulan Ini (2x1) -->
            <a href="{{ route('logistik.index') }}" class="metro-tile metro-tile-2x1 bg-metro-teal">
                <div class="tile-title">Biaya Pakan Bulan Ini</div>
                <div class="tile-content">
                    <h2 class="tile-value fs-3 mb-1" style="font-weight: 800;">Rp {{ number_format($bebanPakanBulanIni, 0, ',', '.') }}</h2>
                    <span class="tile-subtext">Total pengeluaran distribusi pakan</span>
                </div>
                <i class="bi bi-wallet2 tile-icon"></i>
            </a>

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
    </div>
</div>
@endsection