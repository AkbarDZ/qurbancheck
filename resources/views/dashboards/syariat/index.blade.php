@extends('layouts.app')

@push('styles')
<style>
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

@section('title', 'Syariat - Sistem Qurban')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h3 class="fw-bold mb-0 text-dark">Kelayakan Syariat & SKKH</h3>
            <p class="text-muted mb-0">Kelola proses *screening* kecacatan hewan dan arsip legalitas kesehatan (SKKH).
            </p>
        </div>
    </div>

    <!-- Main Tabs Card Wrapper -->
    <div class="card shadow border-0">
        <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
            <!-- Navigasi Tabs -->
            <ul class="nav nav-tabs fw-bold mb-0 border-bottom-0" id="syariatTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-dark px-4 py-3" id="pemeriksaan-tab" data-bs-toggle="tab"
                        data-bs-target="#pemeriksaan-pane" type="button" role="tab">
                        <i class="bi bi-clipboard2-check me-2 text-primary"></i> Pemeriksaan Fisik
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-dark px-4 py-3" id="skkh-tab" data-bs-toggle="tab"
                        data-bs-target="#skkh-pane" type="button" role="tab">
                        <i class="bi bi-file-earmark-pdf me-2 text-success"></i> Arsip Surat SKKH
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <!-- Konten Tabs -->
            <div class="tab-content" id="syariatTabContent">

                <!-- ============================================================== -->
                <!-- TAB 1: PEMERIKSAAN FISIK (INTERNAL) -->
                <!-- ============================================================== -->
                <div class="tab-pane fade show active p-4" id="pemeriksaan-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-clipboard2-check me-2 text-primary"></i>Pemeriksaan Fisik</h5>
                            <p class="text-muted small mb-0">Kelola hasil screening kesehatan dan kelayakan syariat hewan.</p>
                        </div>
                        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTambahPemeriksaan">
                            <i class="bi bi-plus-lg me-1"></i> Mulai Pemeriksaan Baru
                        </button>
                    </div>

                    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr class="border-bottom border-light-subtle">
                                    <th class="py-3 ps-4 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-calendar3 me-2 text-muted"></i>Tanggal</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-tag me-2 text-muted"></i>No. Eartag</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-person me-2 text-muted"></i>Petugas Internal</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-shield-check me-2 text-muted"></i>Status Kelayakan</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-file-earmark-check me-2 text-muted"></i>Status SKKH</th>
                                    <th class="py-3 text-muted fw-bold text-end pe-4" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pemeriksaanContainer">
                                @forelse($pemeriksaans as $cek)
                                    @include('dashboards.syariat.components.row-pemeriksaan', ['cek' => $cek])
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Pemeriksaan Syariat</h6>
                                        <p class="small text-muted mb-0">Klik tombol "Mulai Pemeriksaan Baru" untuk memulai.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center pagination-custom" id="pemeriksaanPaginationContainer">
                        {{ $pemeriksaans->appends(request()->except('pemeriksaan_page'))->links() }}
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- TAB 2: ARSIP SKKH (LEGALITAS DINAS) -->
                <!-- ============================================================== -->
                <div class="tab-pane fade p-4" id="skkh-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-file-earmark-pdf me-2 text-success"></i>Surat Keterangan Kesehatan Hewan (SKKH)</h5>
                            <p class="text-muted small mb-0">Kelola dan arsipkan surat legalitas kesehatan hewan kurban dari Dinas Peternakan.</p>
                        </div>
                        <button class="btn btn-success shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#modalUploadSKKH">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Upload SKKH Kolektif
                        </button>
                    </div>

                    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr class="border-bottom border-light-subtle">
                                    <th class="py-3 ps-4 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-file-earmark-text me-2 text-muted"></i>No. Surat</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-building me-2 text-muted"></i>Instansi Penerbit</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-person-badge me-2 text-muted"></i>Dokter Pemeriksa</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-calendar-event me-2 text-muted"></i>Tgl Terbit</th>
                                    <th class="py-3 text-muted fw-bold text-end pe-4" style="font-size: 0.85rem; width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="skkhTableBody">
                                @forelse($dokumenSkkhs as $doc)
                                    @include('dashboards.syariat.components.row-skkh', ['doc' => $doc])
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Dokumen SKKH</h6>
                                        <p class="small text-muted mb-0">Klik tombol "Upload SKKH Kolektif" untuk mengunggah dokumen baru.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center pagination-custom" id="skkhPaginationContainer">
                        {{ $dokumenSkkhs->appends(request()->except('skkh_page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@include('dashboards.syariat.components.modal-pemeriksaan')
@include('dashboards.syariat.components.modal-detail')
@include('dashboards.syariat.components.modal-skkh')
@include('dashboards.syariat.components.modal-detail-skkh')

@endsection

@include('dashboards.syariat.components.script')
