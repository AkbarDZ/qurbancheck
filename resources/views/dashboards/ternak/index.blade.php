@extends('layouts.app')

@section('title', 'Manajemen Ternak - Sistem Qurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-dark">Data Ternak</h3>
        <p class="text-muted mb-0">Kelola populasi, profil hewan, dan pantau bobot ternak.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTernak">
        <i class="bi bi-plus-lg"></i> Tambah Ternak
    </button>
</div>

<div class="card shadow-sm border-0 mb-4 sticky-top" style="background-color: #5aa17f;">
    <div class="card-body p-3">
        <form action="{{ url('/ternak') }}" method="GET" class="row g-3 align-items-center" id="formFilter">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" name="search"
                        placeholder="Cari Eartag / Nama Panggilan..." value="{{ request('search') }}" id="inputSearchTernak">
                </div>
            </div>

            <div class="col-md-4">
                <select class="form-select filter-ternak" name="kandang_id" id="filterKandang">
                    <option value="">-- Semua Kandang --</option>
                    @foreach($kandangs as $k)
                        <option value="{{ $k->id }}" {{ request('kandang_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kandang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-select filter-ternak" name="ras_id" id="filterRas">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($rasTernaks as $ras)
                        <option value="{{ $ras->id }}" {{ request('ras_id') == $ras->id ? 'selected' : '' }}>
                            {{ optional($ras->tipeTernak)->nama_jenis }} | {{ $ras->nama_ras }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">-- Semua Status --</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="sakit">Sakit</option>
                    <option value="memenuhi_syariat">Memenuhi Syariat</option>
                    <option value="tidak_memenuhi_syariat">Tidak Memenuhi Syariat</option>
                    <option value="sakit">Belum diperiksa</option>
                </select>
            </div> --}}
        </form>
    </div>
</div>

<div class="card shadow border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Daftar Hewan Ternak</h6>
        <span class="badge bg-primary rounded-pill px-3 py-2" id="totalEkorBadge">Total: {{ $ternaks->total() }}
            Ekor</span>
    </div>
    <div class="card-body bg-light p-4">
        <div id="ternakContainer">
            @forelse($ternaks as $ternak)
            @include('dashboards.ternak.components.card-ternak')
            @empty
            <div class="alert alert-white text-center py-5 border-0 shadow-sm bg-white" id="emptyStateTernak">
                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Belum ada data ternak.</h5>
                <p class="text-muted small">Coba ubah filter pencarian Anda atau tambahkan data baru.</p>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4 pagination-custom" id="paginationContainer">
            {{-- Menggunakan withQueryString agar filter pencarian tidak hilang saat pindah halaman --}}
            {{ $ternaks->withQueryString()->links() }}
        </div>

    </div>
</div>

@include('dashboards.ternak.components.modal-tambah')
@include('dashboards.ternak.components.modal-edit')
@include('dashboards.ternak.components.modal-perkembangan-berat')

@push('styles')
<style>
    /* Menyembunyikan teks "Showing X to Y results" bawaan Laravel */
    .pagination-custom div.small.text-muted {
        display: none !important;
    }

    /* Membatalkan space-between bawaan Laravel dan memaksa tombol angka ke tengah */
    .pagination-custom .d-sm-flex {
        justify-content: center !important;
    }

</style>
@endpush

@include('dashboards.ternak.components.script')

@endsection
