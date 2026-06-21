@extends('layouts.app')

@section('title', 'Kesehatan - Sistem Qurban')

@section('content')
<div class="container-fluid">

    <div class="card shadow border-1 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Kesehatan Ternak Qurban</h3>
                <p class="text-muted mb-0">Kelola riwayat medis, gejala, dan tindakan pengobatan hewan.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKesehatan">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pemeriksaan
            </button>
        </div>
    </div>

    <div class="card shadow border-0 mb-4 bg-success">
        <div class="card-body p-3">
            <form action="{{ url('/kesehatan') }}" method="GET" class="row g-3 align-items-center" id="formFilter">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="search"
                            placeholder="Cari Eartag..." value="{{ request('search') }}" id="inputSearchKesehatan">
                    </div>
                </div>

                <div class="col-md-5">
                    <select class="form-select filter-kesehatan" name="karantina" id="filterKarantina">
                        <option value="">-- Semua Status Karantina --</option>
                        <option value="1" {{ request('karantina') == '1' ? 'selected' : '' }}>Sedang Dikarantina
                        </option>
                        <option value="0" {{ request('karantina') == '0' ? 'selected' : '' }}>Tidak Dikarantina</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-1">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold">Riwayat Pemeriksaan & Pengobatan</h6>
            
            <div class="d-flex align-items-center flex-wrap gap-2 ms-auto">
                <!-- Sorting select -->
                <select class="form-select form-select-sm border border-light-subtle bg-white text-dark fw-semibold" id="filterSort" style="width: 140px; cursor: pointer;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Eartag (A-Z)</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Eartag (Z-A)</option>
                </select>

                <!-- Custom Date Range -->
                <div class="input-group input-group-sm" style="width: 280px;">
                    <input type="date" class="form-control border border-light-subtle bg-white text-dark" id="filterStartDate" value="{{ request('start_date') }}" title="Tanggal Mulai">
                    <span class="input-group-text bg-white text-muted border border-light-subtle border-start-0 border-end-0 small">s/d</span>
                    <input type="date" class="form-control border border-light-subtle bg-white text-dark" id="filterEndDate" value="{{ request('end_date') }}" title="Tanggal Selesai">
                </div>

                <span class="badge bg-primary px-3 py-2" id="totalLogBadge">Total:
                    {{ $logKesehatans->total() }} Catatan</span>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr class="border-bottom border-light-subtle">
                            <th class="py-3 ps-4 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-calendar3 me-2 text-muted"></i>Tanggal</th>
                            <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-tag me-2 text-muted"></i>No. Eartag</th>
                            <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-clipboard2-pulse me-2 text-muted"></i>Gejala Klinis</th>
                            <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-activity me-2 text-muted"></i>Tindakan Medis</th>
                            <th class="py-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-exclamation-triangle me-2 text-muted"></i>Status Karantina</th>
                            <th class="py-3 text-muted fw-bold text-end pe-4" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kesehatanContainer">
                        @forelse($logKesehatans as $log)
                        <tr id="row-kesehatan-{{ $log->id }}">
                            <td class="py-3 ps-4">{{ \Carbon\Carbon::parse($log->tanggal_rekam)->format('d M Y') }}</td>
                            <td class="py-3">
                                <span class="fw-bold text-primary">{{ $log->ternak->nomor_eartag }}</span>
                                @if($log->ternak->nama_panggilan)
                                <small class="d-block text-muted">{{ $log->ternak->nama_panggilan }}</small>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                    title="{{ $log->gejala }}">
                                    {{ $log->gejala }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($log->pengobatans->count() > 0)
                                <span
                                    class="badge bg-light text-dark border">{{ $log->pengobatans->first()->nama_obat_tindakan }}</span>
                                @if($log->pengobatans->count() > 1)
                                <span class="badge bg-secondary">+{{ $log->pengobatans->count() - 1 }}</span>
                                @endif
                                @else
                                <span class="text-muted small">Belum ada tindakan</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($log->status_karantina)
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i> Karantina</span>
                                @else
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> Aman</span>
                                @endif
                            </td>
                            <td class="py-3 text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary me-1 btn-detail-kesehatan"
                                    data-id="{{ $log->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if(Auth::user()->role === 'owner/admin')
                                <button class="btn btn-sm btn-outline-danger btn-delete-kesehatan"
                                    data-id="{{ $log->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Log">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyStateKesehatan">
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard2-pulse fs-1 d-block mb-2"></i>
                                Belum ada riwayat kesehatan yang dicatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center pagination-custom" id="paginationContainer">
                {{ $logKesehatans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@include('dashboards.kesehatan.components.modal-tambah')
@include('dashboards.kesehatan.components.modal-detail')

@endsection

@push('styles')
<style>
    .pagination-custom div.small.text-muted {
        display: none !important;
    }

    .pagination-custom .d-sm-flex {
        justify-content: center !important;
    }

</style>
@endpush

@push('scripts')
<script>
    // FUNGSI GLOBAL: Template Render Baris Tabel Kesehatan
    window.renderKesehatanRowHTML = function (data) {

        // Memformat Tanggal
        let dateObj = new Date(data.tanggal_rekam);
        let options = {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        };
        let formattedDate = dateObj.toLocaleDateString('id-ID', options); // Cth: 12 Agu 2026

        // Mengatur Nama Hewan
        let namaPanggilan = data.ternak.nama_panggilan ?
            `<small class="d-block text-muted">${data.ternak.nama_panggilan}</small>` : '';

        let tindakanHtml = '<span class="text-muted small">Belum ada tindakan</span>';

        if (data.pengobatans && data.pengobatans.length > 0) {
            tindakanHtml =
                `<span class="badge bg-light text-dark border">${data.pengobatans[0].nama_obat_tindakan}</span>`;
            if (data.pengobatans.length > 1) {
                tindakanHtml += ` <span class="badge bg-secondary">+${data.pengobatans.length - 1}</span>`;
            }
        }

        let isKarantina = data.status_karantina === 1 || data.status_karantina === true;

        let karantinaHtml = isKarantina ?
            `<span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i> Karantina</span>` :
            `<span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> Aman</span>`;

        return `
            <td class="py-3 ps-4">${formattedDate}</td>
            <td class="py-3">
                <span class="fw-bold text-primary">${data.ternak.nomor_eartag}</span>
                ${namaPanggilan}
            </td>
            <td class="py-3">
                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="${data.gejala}">
                    ${data.gejala}
                </span>
            </td>
            <td class="py-3">${tindakanHtml}</td>
            <td class="py-3">${karantinaHtml}</td>
            <td class="py-3 text-end pe-4">
                <button class="btn btn-sm btn-outline-secondary me-1 btn-detail-kesehatan" data-id="${data.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                    <i class="bi bi-eye"></i>
                </button>
                @if(Auth::user()->role === 'owner/admin')
                <button class="btn btn-sm btn-outline-danger btn-delete-kesehatan" data-id="${data.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Log">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </td>
        `;
    };

    window.updateCounterKesehatan = function (amount) {
        let badgeTotal = document.getElementById('totalLogBadge');
        if (badgeTotal) {
            let currentNum = parseInt(badgeTotal.innerText.replace(/\D/g, '')) || 0;
            badgeTotal.innerText = `Total: ${currentNum + amount} Catatan`;
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        const containerKesehatan = document.getElementById('kesehatanContainer');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        // Initialize Tooltips
        function initTooltips(context = document) {
            let triggers = context.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...triggers].forEach(el => {
                let instance = bootstrap.Tooltip.getInstance(el);
                if (instance) instance.dispose();
                new bootstrap.Tooltip(el);
            });
        }
        initTooltips();

        // ========== AJAX FILTER & SEARCH ==========
        const formFilter = document.getElementById('formFilter');
        const paginationContainer = document.getElementById('paginationContainer');
        const totalBadge = document.getElementById('totalLogBadge');
        let searchTimer = null;

        function fetchKesehatan(queryString) {
            containerKesehatan.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        <span class="ms-2">Memuat data...</span>
                    </td>
                </tr>`;

            fetch(`/kesehatan?${queryString}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.data && data.data.length > 0) {
                        let html = '';
                        data.data.forEach(log => {
                            let tr = document.createElement('tr');
                            tr.id = `row-kesehatan-${log.id}`;
                            tr.innerHTML = window.renderKesehatanRowHTML(log);
                            html += tr.outerHTML;
                        });
                        containerKesehatan.innerHTML = html;
                        initTooltips(containerKesehatan);
                    } else {
                        containerKesehatan.innerHTML = `
                            <tr id="emptyStateKesehatan">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard2-pulse fs-1 d-block mb-2"></i>
                                    Tidak ada riwayat kesehatan ditemukan.
                                </td>
                            </tr>`;
                    }

                    paginationContainer.innerHTML = data.pagination || '';
                    totalBadge.innerText = `Total: ${data.total} Catatan`;

                    const newUrl = `/kesehatan${queryString ? '?' + queryString : ''}`;
                    history.replaceState(null, '', newUrl);
                }
            })
            .catch(err => {
                console.error(err);
                containerKesehatan.innerHTML = `
                    <tr>
                        <td colspan="6" class="alert alert-danger text-center py-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Gagal memuat data. Silakan coba lagi.
                        </td>
                    </tr>`;
            });
        }

        formFilter.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(formFilter);

            // Append header filters
            const sortVal = document.getElementById('filterSort').value;
            const startDateVal = document.getElementById('filterStartDate').value;
            const endDateVal = document.getElementById('filterEndDate').value;
            
            if (sortVal) formData.append('sort', sortVal);
            if (startDateVal) formData.append('start_date', startDateVal);
            if (endDateVal) formData.append('end_date', endDateVal);

            let params = new URLSearchParams(formData);
            for (let [key, val] of [...params.entries()]) {
                if (!val) params.delete(key);
            }
            fetchKesehatan(params.toString());
        });

        document.querySelectorAll('.filter-kesehatan').forEach(select => {
            select.addEventListener('change', function() {
                formFilter.dispatchEvent(new Event('submit'));
            });
        });

        // Trigger submit when header filters change
        document.getElementById('filterSort').addEventListener('change', function() {
            formFilter.dispatchEvent(new Event('submit'));
        });
        document.getElementById('filterStartDate').addEventListener('change', function() {
            formFilter.dispatchEvent(new Event('submit'));
        });
        document.getElementById('filterEndDate').addEventListener('change', function() {
            formFilter.dispatchEvent(new Event('submit'));
        });

        const inputSearch = document.getElementById('inputSearchKesehatan');
        if (inputSearch) {
            inputSearch.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    formFilter.dispatchEvent(new Event('submit'));
                }, 400);
            });
        }

        paginationContainer.addEventListener('click', function(e) {
            let link = e.target.closest('a');
            if (link && link.href) {
                e.preventDefault();
                let url = new URL(link.href);
                fetchKesehatan(url.searchParams.toString());
            }
        });
        // ========== END AJAX FILTER & SEARCH ==========

        // EVENT DELEGATION: Menangani klik tombol Hapus/Detail di baris tabel
        containerKesehatan.addEventListener('click', function (e) {

            // TOMBOL HAPUS LOG KESEHATAN
            let btnDelete = e.target.closest('.btn-delete-kesehatan');
            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');
                Swal.fire({
                    title: 'Hapus Pemeriksaan?',
                    text: "Hapus catatan pemeriksaan ini? Semua data tindakan dan pengobatan di dalamnya juga akan terhapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btnDelete.disabled = true;

                        fetch(`/kesehatan/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById(`row-kesehatan-${id}`).remove();
                                    window.updateCounterKesehatan(-1);

                                    // Tampilkan state kosong jika tabel habis
                                    if (containerKesehatan.querySelectorAll('tr').length === 0) {
                                        containerKesehatan.innerHTML = `
                                        <tr id="emptyStateKesehatan">
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-clipboard2-pulse fs-1 d-block mb-2"></i>
                                                Belum ada riwayat kesehatan yang dicatat.
                                            </td>
                                        </tr>`;
                                    }
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#428475'
                                    });
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    title: 'Gagal',
                                    text: "Gagal menghapus data.",
                                    icon: 'error',
                                    confirmButtonColor: '#428475'
                                });
                                btnDelete.disabled = false;
                            });
                    }
                });
            }

            // TOMBOL DETAIL KESEHATAN
            let btnDetail = e.target.closest('.btn-detail-kesehatan');
            if (btnDetail) {
                let id = btnDetail.getAttribute('data-id');
                
                // Siapkan Modal & Elemen UI
                let modalDetail = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetailKesehatan'));
                let loading = document.getElementById('loadingDetail');
                let konten = document.getElementById('kontenDetail');
                
                // Tampilkan Loading, Sembunyikan Konten
                loading.classList.remove('d-none');
                konten.classList.add('d-none');
                modalDetail.show();

                fetch(`/kesehatan/${id}`, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let log = data.data;

                        // 1. Info Hewan
                        document.getElementById('detail_eartag').innerText = log.ternak.nomor_eartag;
                        document.getElementById('detail_nama_hewan').innerText = log.ternak.nama_panggilan || 'Tidak ada nama panggilan';
                        
                        // 2. Info Pemeriksaan
                        let dateObj = new Date(log.tanggal_rekam);
                        document.getElementById('detail_tanggal').innerText = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                        // Asumsi tabel users memiliki kolom 'name', ganti jika kolomnya bernama lain
                        document.getElementById('detail_petugas').innerText = log.penanggung_jawab ? log.penanggung_jawab.name : 'Tidak diketahui (Sistem)';

                        // 3. Gejala & Foto
                        document.getElementById('detail_gejala').innerText = log.gejala;
                        let containerFoto = document.getElementById('container_foto_gejala');
                        let imgFoto = document.getElementById('detail_foto');
                        
                        if(log.dir_foto_gejala) {
                            imgFoto.src = `/storage/${log.dir_foto_gejala}`;
                            containerFoto.classList.remove('d-none');
                        } else {
                            imgFoto.src = "";
                            containerFoto.classList.add('d-none');
                        }

                        // 4. Pengobatan & Tindakan
                        let tbodyPengobatan = document.getElementById('detail_tbody_pengobatan');
                        let badgeKarantina = document.getElementById('detail_badge_karantina');
                        tbodyPengobatan.innerHTML = ''; // Bersihkan tabel obat lama
                        
                        let isKarantina = log.status_karantina === 1 || log.status_karantina === true;
                        let totalBiaya = 0;

                        if(log.pengobatans && log.pengobatans.length > 0) {
                            log.pengobatans.forEach(obat => {
                                let biaya = parseFloat(obat.biaya_pengobatan) || 0;
                                totalBiaya += biaya;

                                // Format Rupiah
                                let biayaFormatted = biaya > 0 ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(biaya) : '-';

                                tbodyPengobatan.innerHTML += `
                                    <tr>
                                        <td class="fw-semibold text-dark">${obat.nama_obat_tindakan}</td>
                                        <td>${obat.dosis || '-'}</td>
                                        <td>${obat.catatan || '-'}</td>
                                        <td class="text-end text-primary">${biayaFormatted}</td>
                                    </tr>
                                `;
                            });
                            
                            // Tampilkan total biaya jika ada
                            let tfootBiaya = document.getElementById('tfoot_total_biaya');
                            if(totalBiaya > 0) {
                                document.getElementById('detail_total_biaya').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalBiaya);
                                tfootBiaya.classList.remove('d-none');
                            } else {
                                tfootBiaya.classList.add('d-none');
                            }

                        } else {
                            tbodyPengobatan.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">Tidak ada catatan tindakan/pengobatan.</td></tr>`;
                            document.getElementById('tfoot_total_biaya').classList.add('d-none');
                        }

                        // Atur Badge Karantina
                        if(isKarantina) {
                            badgeKarantina.classList.remove('d-none');
                        } else {
                            badgeKarantina.classList.add('d-none');
                        }

                        // Matikan Loading, Tampilkan Konten
                        loading.classList.add('d-none');
                        konten.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mengambil detail data kesehatan.");
                    modalDetail.hide();
                });
            }
        });

        // Auto-open modal if redirected with action=tambah or a specific ternak ID
        const urlParams = new URLSearchParams(window.location.search);
        const tambahTernakId = urlParams.get('tambah_ternak_id');
        const action = urlParams.get('action');
        if (action === 'tambah' || tambahTernakId) {
            if (tambahTernakId) {
                const selectTernak = document.querySelector('#formTambahKesehatan select[name="ternak_id"]');
                if (selectTernak) {
                    selectTernak.value = tambahTernakId;
                }
            }
            
            const modalTambah = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKesehatan'));
            if (modalTambah) {
                modalTambah.show();
            }

            // Clean the URL query parameters so reloading doesn't open the modal again
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({path: cleanUrl}, '', cleanUrl);
        }
    });

</script>
@endpush
