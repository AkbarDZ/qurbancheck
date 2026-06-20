@extends('layouts.app')

@section('title', 'Logistik Pakan - Sistem Qurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-dark">Logistik & Distribusi Pakan</h3>
        <p class="text-muted mb-0">Kelola stok gudang dan catat konsumsi pakan per kandang untuk perhitungan modal harian.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow border-0">
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
                        <form id="formDistribusi">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tanggal Distribusi</label>
                                <input type="date" class="form-control" name="tanggal_pemberian" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Pilih Pakan</label>
                                <select class="form-select" name="pakan_id" id="selectPakan" required>
                                    <option value="" selected disabled>-- Pilih Pakan dari Gudang --</option>
                                    @foreach($pakans as $pakan)
                                    <option value="{{ $pakan->id }}" data-harga="{{ $pakan->harga_per_kg }}"
                                        data-stok="{{ $pakan->stok_kg }}">
                                        {{ $pakan->nama_pakan }} (Stok: {{ $pakan->stok_kg }} Kg)
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tujuan Kandang</label>
                                <select class="form-select" name="kandang_id" required>
                                    <option value="" selected disabled>-- Pilih Kandang --</option>
                                    @foreach($kandangs as $kandang)
                                    <option value="{{ $kandang->id }}">{{ $kandang->nama_kandang }} (Isi:
                                        {{ $kandang->ternaks_count }} Ekor)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Jumlah Pakan (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control" name="jumlah_kg" id="inputJumlah"
                                        placeholder="0" required>
                                    <span class="input-group-text bg-light">Kg</span>
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
                    <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahPakan">
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
                    <input type="text" class="form-control" name="nama_pakan" placeholder="Misal: Konsentrat Pedaging" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Harga Beli per Kg</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="harga_per_kg" placeholder="0" required>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Jumlah Stok Masuk</label>
                        <div class="input-group">
                            <input type="number" step="0.1" class="form-control" name="stok_kg" placeholder="0" required>
                            <span class="input-group-text">Kg</span>
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // --- 1. LOGIKA AUTO-KALKULASI TOTAL BIAYA ---
        const selectPakan = document.getElementById('selectPakan');
        const inputJumlah = document.getElementById('inputJumlah');
        const labelHarga = document.getElementById('labelHargaKg');
        const labelTotal = document.getElementById('labelTotalBiaya');

        let currentHargaPerKg = 0;

        // Format Rupiah Standar
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Fungsi Kalkulasi
        function hitungTotal() {
            let jumlah = parseFloat(inputJumlah.value) || 0;
            let total = currentHargaPerKg * jumlah;
            labelTotal.innerText = formatRupiah(total);
        }

        // Listener saat Dropdown Pakan diubah
        if (selectPakan) {
            selectPakan.addEventListener('change', function () {
                let selectedOption = this.options[this.selectedIndex];
                currentHargaPerKg = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                labelHarga.innerText = formatRupiah(currentHargaPerKg);
                hitungTotal();
            });
        }

        // Listener saat input Jumlah diketik
        if (inputJumlah) {
            inputJumlah.addEventListener('input', hitungTotal);
        }


        // --- 2. LOGIKA AJAX SUBMIT DISTRIBUSI ---
        const formDistribusi = document.getElementById('formDistribusi');
        const btnSimpan = document.getElementById('btnSimpanDistribusi');
        const errorAlert = document.getElementById('error_distribusi');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (formDistribusi) {
            formDistribusi.addEventListener('submit', function (e) {
                e.preventDefault();

                errorAlert.classList.add('d-none');
                let originalText = btnSimpan.innerHTML;
                btnSimpan.innerHTML =
                    '<span class="spinner-border spinner-border-sm"></span> Memproses...';
                btnSimpan.disabled = true;

                const formData = new FormData(formDistribusi);

                fetch('/logistik/distribusi', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(async response => {
                        const isJson = response.headers.get('content-type')?.includes('application/json');
                        if (!response.ok) {
                            let errorMsg = 'Terjadi kesalahan sistem.';
                            if (isJson) {
                                const errData = await response.json();
                                errorMsg = errData.message || Object.values(errData.errors)[0][0] || errorMsg;
                            }
                            return Promise.reject(errorMsg);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Refresh halaman untuk mengupdate stok gudang dan riwayat tabel
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        errorAlert.innerText = error;
                        errorAlert.classList.remove('d-none');
                    })
                    .finally(() => {
                        btnSimpan.innerHTML = originalText;
                        btnSimpan.disabled = false;
                    });
            });
        }

        // --- 3. LOGIKA CLIENT-SIDE PAGINATION ---
        window.initTablePagination = function (tableBodyId, paginationId, itemsPerPage = 4) {
            const tbody = document.getElementById(tableBodyId);
            const paginationContainer = document.getElementById(paginationId);
            if (!tbody || !paginationContainer) return null;

            let currentPage = 1;

            function render() {
                const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => {
                    return !tr.querySelector('td[colspan]');
                });

                const totalItems = rows.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage);

                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = totalPages;
                }

                rows.forEach((row, index) => {
                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    if (index >= start && index < end) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }

                    // Update Nomor
                    const firstTd = row.querySelector('td:first-child');
                    if (firstTd && !isNaN(parseInt(firstTd.innerText))) {
                        firstTd.innerText = index + 1;
                    }
                });

                paginationContainer.innerHTML = '';
                
                if (totalPages <= 1) {
                    return;
                }

                const nav = document.createElement('nav');
                const ul = document.createElement('ul');
                ul.className = 'pagination pagination-sm mb-0 gap-1';

                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                const prevA = document.createElement('a');
                prevA.className = 'page-link rounded-2';
                prevA.href = '#';
                prevA.innerHTML = '&laquo;';
                prevA.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        render();
                    }
                });
                prevLi.appendChild(prevA);
                ul.appendChild(prevLi);

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${currentPage === i ? 'active' : ''}`;
                    const a = document.createElement('a');
                    a.className = 'page-link rounded-2';
                    a.href = '#';
                    a.innerText = i;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        render();
                    });
                    li.appendChild(a);
                    ul.appendChild(li);
                }

                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                const nextA = document.createElement('a');
                nextA.className = 'page-link rounded-2';
                nextA.href = '#';
                nextA.innerHTML = '&raquo;';
                nextA.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        render();
                    }
                });
                nextLi.appendChild(nextA);
                ul.appendChild(nextLi);

                nav.appendChild(ul);
                paginationContainer.appendChild(nav);
            }

            render();
        };

        window.initTablePagination('tbodyGudang', 'paginationGudang', 4);
        window.initTablePagination('tbodyRiwayat', 'paginationRiwayat', 4);

    });
</script>
@endpush
