@extends('layouts.app')

@section('title', 'Manajemen Ternak - Sistem Qurban')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow border-1 mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Data Ternak</h3>
            <p class="text-muted mb-0">Kelola populasi, profil hewan, dan pantau bobot ternak.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTernak">
            <i class="bi bi-plus-lg"></i> Tambah Ternak
        </button>
    </div>
</div>

<div class="card shadow-sm border bg-success mb-4 sticky-top">
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

<div class="card shadow border-1 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="mb-0 fw-bold">Daftar Hewan Ternak</h6>
        
        <div class="d-flex align-items-center flex-wrap gap-2 ms-auto">
            <!-- Sorting select -->
            <select class="form-select form-select-sm border border-light-subtle bg-white text-dark fw-semibold" id="filterSort" style="width: 150px; cursor: pointer;">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Nama/Eartag (A-Z)</option>
                <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Nama/Eartag (Z-A)</option>
            </select>

            <!-- Custom Date Range -->
            <div class="input-group input-group-sm" style="width: 280px;">
                <input type="date" class="form-control border border-light-subtle bg-white text-dark" id="filterStartDate" value="{{ request('start_date') }}" title="Tanggal Masuk Mulai">
                <span class="input-group-text bg-white text-muted border border-light-subtle border-start-0 border-end-0 small">s/d</span>
                <input type="date" class="form-control border border-light-subtle bg-white text-dark" id="filterEndDate" value="{{ request('end_date') }}" title="Tanggal Masuk Selesai">
            </div>

            <span class="badge bg-primary px-3 py-2" id="totalEkorBadge">Total: {{ $ternaks->total() }} Ekor</span>
        </div>
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
@include('dashboards.ternak.components.modal-keuangan')

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

    /* Styling to make Select2 fit Bootstrap 5 fields nicely */
    .select2-container--default .select2-selection--single {
        border: 1px solid #dee2e6;
        height: 38px;
        line-height: 38px;
        border-radius: 0.375rem;
        padding-top: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection--arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection--rendered {
        color: #212529;
    }
    .select2-dropdown {
        border-color: #dee2e6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5aa17f;
    }
</style>
@endpush

@include('dashboards.ternak.components.script')

@push('scripts')
@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (old('_method') === 'PUT')
            let editModal = new bootstrap.Modal(document.getElementById('modalEditTernak'));
            editModal.show();
            let ternakId = "{{ old('id') }}";
            let btn = document.querySelector(`.btn-edit-ternak[data-id="${ternakId}"]`);
            if (btn) {
                setTimeout(() => {
                    btn.click();
                    setTimeout(() => {
                        @if(old('nomor_eartag')) document.getElementById('edit_nomor_eartag').value = "{{ old('nomor_eartag') }}"; @endif
                        @if(old('ras_id')) 
                            document.getElementById('edit_ras_id').value = "{{ old('ras_id') }}";
                            $('#edit_ras_id').trigger('change');
                        @endif
                        @if(old('jenis_kelamin')) document.getElementById('edit_jenis_kelamin').value = "{{ old('jenis_kelamin') }}"; @endif
                        @if(old('kandang_id')) 
                            document.getElementById('edit_kandang_id').value = "{{ old('kandang_id') }}";
                            $('#edit_kandang_id').trigger('change');
                        @endif
                        @if(old('asal_hewan'))
                            let oldAsal = "{{ old('asal_hewan') }}";
                            if (oldAsal === 'beli') {
                                document.getElementById('edit_asal_beli').checked = true;
                            } else {
                                document.getElementById('edit_asal_lahir').checked = true;
                            }
                            if (typeof window.editToggleAsalUsul === 'function') {
                                window.editToggleAsalUsul();
                            }
                        @endif
                        @if(old('harga_beli_awal')) document.getElementById('edit_harga_beli_awal').value = "{{ old('harga_beli_awal') }}"; @endif
                        @if(old('umur_bulan_beli')) document.getElementById('edit_umur_bulan_beli').value = "{{ old('umur_bulan_beli') }}"; @endif
                        @if(old('tanggal_lahir')) document.getElementById('edit_tanggal_lahir').value = "{{ old('tanggal_lahir') }}"; @endif
                    }, 150);
                }, 100);
            }
        @elseif (old('_method') === 'PATCH')
            let modalNama = new bootstrap.Modal(document.getElementById('modalNamaPanggilan'));
            modalNama.show();
            let ternakId = "{{ old('id') }}";
            let trigger = document.querySelector(`.trigger-nama[data-id="${ternakId}"]`);
            if (trigger) {
                setTimeout(() => {
                    trigger.click();
                    setTimeout(() => {
                        @if(old('nama_panggilan')) document.getElementById('input_nama_panggilan').value = "{{ old('nama_panggilan') }}"; @endif
                    }, 150);
                }, 100);
            }
        @elseif ($errors->has('berat_kg') || $errors->has('tanggal_timbang'))
            let modalBerat = new bootstrap.Modal(document.getElementById('modalPerkembanganBerat'));
            modalBerat.show();
            let ternakId = "{{ old('id') }}";
            let btn = document.querySelector(`.btn-perkembangan-berat[data-id="${ternakId}"]`);
            if (btn) {
                setTimeout(() => {
                    btn.click();
                    setTimeout(() => {
                        @if(old('berat_kg')) document.getElementById('input_berat_kg').value = "{{ old('berat_kg') }}"; @endif
                        @if(old('tanggal_timbang')) document.getElementById('input_tanggal_timbang').value = "{{ old('tanggal_timbang') }}"; @endif
                    }, 150);
                }, 100);
            }
        @else
            let tambahModal = new bootstrap.Modal(document.getElementById('modalTambahTernak'));
            tambahModal.show();
        @endif
    });
</script>
@endif
@endpush

@endsection
