@extends('layouts.app')

@section('title', 'Logistik Pakan - Sistem Qurban')

@section('content')
<div class="card shadow border-1 mb-4">
    <div class="card-body">
        <h3 class="fw-bold mb-0 text-dark">Logistik & Distribusi Pakan</h3>
        <p class="text-muted mb-0">Kelola stok gudang dan catat konsumsi pakan per kandang untuk perhitungan modal harian.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow border-1">
    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
        <ul class="nav nav-tabs" id="logistikTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-semibold" id="distribusi-tab" data-bs-toggle="tab"
                    data-bs-target="#distribusi-pane" type="button" role="tab">Distribusi Pakan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="gudang-tab" data-bs-toggle="tab"
                    data-bs-target="#gudang-pane" type="button" role="tab">Gudang Pakan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="riwayat-tab" data-bs-toggle="tab"
                    data-bs-target="#riwayat-pane" type="button" role="tab">Riwayat Distribusi</button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="logistikTabContent">
            
            <!-- TAB: DISTRIBUSI PAKAN -->
            <div class="tab-pane fade show active p-4" id="distribusi-pane" role="tabpanel" aria-labelledby="distribusi-tab">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="alert alert-danger d-none py-2 small" id="error_distribusi"></div>
                        <form id="formDistribusi" action="{{ url('/logistik/distribusi') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tanggal Distribusi</label>
                                <input type="date" class="form-control @error('tanggal_pemberian') is-invalid @enderror" name="tanggal_pemberian" value="{{ old('tanggal_pemberian', date('Y-m-d')) }}"
                                    required>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tanggal_pemberian" style="font-size: 0.75rem;">
                                    @error('tanggal_pemberian') {{ $message }} @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Pilih Pakan</label>
                                <select class="form-select @error('pakan_id') is-invalid @enderror" name="pakan_id" id="selectPakan" required>
                                    <option value="" disabled {{ old('pakan_id') ? '' : 'selected' }}>-- Pilih Pakan dari Gudang --</option>
                                    @foreach($pakans as $pakan)
                                    <option value="{{ $pakan->id }}" data-harga="{{ $pakan->harga_per_kg }}"
                                        data-stok="{{ $pakan->stok_kg }}" {{ old('pakan_id') == $pakan->id ? 'selected' : '' }}>
                                        {{ $pakan->nama_pakan }} (Stok: {{ $pakan->stok_kg }} Kg)
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_pakan_id" style="font-size: 0.75rem;">
                                    @error('pakan_id') {{ $message }} @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tujuan Kandang</label>
                                <select class="form-select @error('kandang_id') is-invalid @enderror" name="kandang_id" required>
                                    <option value="" disabled {{ old('kandang_id') ? '' : 'selected' }}>-- Pilih Kandang --</option>
                                    @foreach($kandangs as $kandang)
                                    <option value="{{ $kandang->id }}" {{ old('kandang_id') == $kandang->id ? 'selected' : '' }}>{{ $kandang->nama_kandang }} (Isi:
                                        {{ $kandang->ternaks_count }} Ekor)</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_kandang_id" style="font-size: 0.75rem;">
                                    @error('kandang_id') {{ $message }} @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Jumlah Pakan (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control @error('jumlah_kg') is-invalid @enderror" name="jumlah_kg" id="inputJumlah"
                                        placeholder="0" value="{{ old('jumlah_kg') }}" required>
                                    <span class="input-group-text bg-light">Kg</span>
                                </div>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_jumlah_kg" style="font-size: 0.75rem;">
                                    @error('jumlah_kg') {{ $message }} @enderror
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded border mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted fw-semibold">Harga / Kg:</span>
                                    <span class="fw-bold text-dark" id="labelHargaKg">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <span class="small text-muted fw-semibold">Total Biaya Beban:</span>
                                    <span class="fw-bold text-danger fs-5" id="labelTotalBiaya">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold" id="btnSimpanDistribusi">
                                <i class="bi bi-send-check me-2"></i> Proses Distribusi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB: GUDANG PAKAN -->
            <div class="tab-pane fade p-4" id="gudang-pane" role="tabpanel" aria-labelledby="gudang-tab">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahPakan">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Stok
                    </button>
                </div>
                <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr class="border-bottom border-light-subtle">
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-box-seam me-2 text-muted"></i>Nama Pakan</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-stack me-2 text-muted"></i>Stok Saat Ini</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-tag me-2 text-muted"></i>Harga / Kg</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-cash-stack me-2 text-muted"></i>Total Nilai Aset</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyGudang">
                            @forelse($pakans as $pakan)
                            <tr>
                                <td class="py-3 px-3 fw-semibold text-secondary">1</td>
                                <td class="py-3 px-3 fw-bold text-dark">{{ $pakan->nama_pakan }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge {{ $pakan->stok_kg > 50 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                        {{ $pakan->stok_kg }} Kg
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-muted fw-semibold">Rp {{ number_format($pakan->harga_per_kg, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 fw-bold text-primary">Rp {{ number_format($pakan->stok_kg * $pakan->harga_per_kg, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Gudang pakan masih kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="paginationGudang" class="d-flex justify-content-center mt-3"></div>
            </div>

            <!-- TAB: RIWAYAT DISTRIBUSI -->
            <div class="tab-pane fade p-4" id="riwayat-pane" role="tabpanel" aria-labelledby="riwayat-tab">
                <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr class="border-bottom border-light-subtle">
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-calendar-event me-2 text-muted"></i>Tanggal</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-geo-alt me-2 text-muted"></i>Tujuan</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-box-seam me-2 text-muted"></i>Jenis Pakan</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-stack me-2 text-muted"></i>Jumlah</th>
                                <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-cash me-2 text-muted"></i>Beban Biaya</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRiwayat">
                            @forelse($distribusis as $dist)
                            <tr>
                                <td class="py-3 px-3 fw-semibold text-secondary">1</td>
                                <td class="py-3 px-3 text-muted small"><i class="bi bi-clock me-1 opacity-50"></i>{{ \Carbon\Carbon::parse($dist->tanggal_pemberian)->format('d M Y') }}</td>
                                <td class="py-3 px-3 fw-bold text-dark">{{ $dist->kandang->nama_kandang }}</td>
                                <td class="py-3 px-3 text-muted fw-semibold">{{ $dist->pakan->nama_pakan }}</td>
                                <td class="py-3 px-3 fw-bold text-dark">{{ $dist->jumlah_kg }} Kg</td>
                                <td class="py-3 px-3 text-danger fw-bold">Rp {{ number_format($dist->total_biaya, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat distribusi pakan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="paginationRiwayat" class="d-flex justify-content-center mt-3"></div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPakan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content border-0 shadow" method="POST" action="{{ url('/logistik/pakan') }}">
            @csrf
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah / Beli Pakan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nama Pakan</label>
                    <input type="text" class="form-control @error('nama_pakan') is-invalid @enderror" name="nama_pakan" value="{{ old('nama_pakan') }}" placeholder="Misal: Konsentrat Pedaging" required>
                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nama_pakan" style="font-size: 0.75rem;">
                        @error('nama_pakan') {{ $message }} @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Harga Beli per Kg</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga_per_kg') is-invalid @enderror" name="harga_per_kg" value="{{ old('harga_per_kg') }}" placeholder="0" required>
                        </div>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_harga_per_kg" style="font-size: 0.75rem;">
                            @error('harga_per_kg') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Jumlah Stok Masuk</label>
                        <div class="input-group">
                            <input type="number" step="0.1" class="form-control @error('stok_kg') is-invalid @enderror" name="stok_kg" value="{{ old('stok_kg') }}" placeholder="0" required>
                            <span class="input-group-text">Kg</span>
                        </div>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_stok_kg" style="font-size: 0.75rem;">
                            @error('stok_kg') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan ke Gudang</button>
            </div>
        </form>
    </div>
</div>

@endsection
@include('dashboards.logistik.components.script')
