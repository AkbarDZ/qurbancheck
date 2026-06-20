@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById('pemeriksaanContainer');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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

        // LOGIKA MODAL SKKH (TAB 2)
        // ---------------------------------------------------------

        // 1. Check All Logic & Select2 Filters
        const checkAllSkkh = document.getElementById('checkAllSkkh');
        const filterTipeSkkh = document.getElementById('filterTipeSkkh');
        const filterKandangSkkh = document.getElementById('filterKandangSkkh');

        // Initialize Select2 when modal is shown
        $('#modalUploadSKKH').on('shown.bs.modal', function () {
            $('#filterTipeSkkh').select2({
                dropdownParent: $('#modalUploadSKKH'),
                width: '100%'
            });
            $('#filterKandangSkkh').select2({
                dropdownParent: $('#modalUploadSKKH'),
                width: '100%'
            });
        });

        function applySkkhFilters() {
            const tipeVal = filterTipeSkkh ? filterTipeSkkh.value : '';
            const kandangVal = filterKandangSkkh ? filterKandangSkkh.value : '';
            const rows = document.querySelectorAll('.skkh-row');

            rows.forEach(row => {
                const rowTipe = row.getAttribute('data-tipe-id');
                const rowKandang = row.getAttribute('data-kandang-id');

                const matchTipe = !tipeVal || rowTipe === tipeVal;
                const matchKandang = !kandangVal || rowKandang === kandangVal;

                if (matchTipe && matchKandang) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck if hidden
                    const cb = row.querySelector('.skkh-checkbox');
                    if (cb) {
                        cb.checked = false;
                    }
                }
            });

            updateSkkhCheckAllState();
        }

        // Bind Select2 change event using jQuery
        $('#filterTipeSkkh, #filterKandangSkkh').on('change', applySkkhFilters);

        function updateSkkhCheckAllState() {
            if (!checkAllSkkh) return;
            const visibleCheckboxes = Array.from(document.querySelectorAll('.skkh-row'))
                .filter(row => row.style.display !== 'none')
                .map(row => row.querySelector('.skkh-checkbox'))
                .filter(cb => cb && !cb.disabled);

            if (visibleCheckboxes.length === 0) {
                checkAllSkkh.checked = false;
                return;
            }

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            checkAllSkkh.checked = allChecked;
        }

        if (checkAllSkkh) {
            checkAllSkkh.addEventListener('change', function () {
                const rows = document.querySelectorAll('.skkh-row');
                rows.forEach(row => {
                    const cb = row.querySelector('.skkh-checkbox');
                    if (cb && !cb.disabled && row.style.display !== 'none') {
                        cb.checked = checkAllSkkh.checked;
                    }
                });
            });
        }

        const skkhCheckboxes = document.querySelectorAll('.skkh-checkbox');
        skkhCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSkkhCheckAllState);
        });

        // 2. Submit Form SKKH (AJAX)
        const formSkkh = document.getElementById('formUploadSKKH');
        const btnSimpanSkkh = document.getElementById('btnSimpanSKKH');

        if (formSkkh) {
            formSkkh.addEventListener('submit', function (e) {
                e.preventDefault();

                let originalText = btnSimpanSkkh.innerHTML;
                btnSimpanSkkh.innerHTML =
                    '<span class="spinner-border spinner-border-sm"></span> Mengunggah...';
                btnSimpanSkkh.disabled = true;

                document.getElementById('error_global_skkh').classList.add('d-none');
                document.querySelectorAll('#formUploadSKKH .is-invalid').forEach(el => el.classList
                    .remove('is-invalid'));
                document.querySelectorAll('#formUploadSKKH .invalid-feedback').forEach(el => el
                    .innerText = '');

                const formData = new FormData(formSkkh);

                fetch('/syariat/skkh', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(async response => {
                        const isJson = response.headers.get('content-type')?.includes(
                            'application/json');
                        if (!response.ok) {
                            if (response.status === 422 && isJson) {
                                const errData = await response.json();
                                return Promise.reject({
                                    type: 'validation',
                                    errors: errData.errors
                                });
                            }
                            let errorMsg = 'Terjadi kesalahan internal.';
                            if (isJson) {
                                const errData = await response.json();
                                errorMsg = errData.message || errorMsg;
                            }
                            return Promise.reject({
                                type: 'server',
                                message: errorMsg
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        if (error.type === 'validation') {
                            for (const [key, messages] of Object.entries(error.errors || {})) {
                                let inputEl = document.querySelector(
                                    `#formUploadSKKH [name="${key}"]`);
                                if (inputEl) {
                                    inputEl.classList.add('is-invalid');
                                    let errorEl = document.getElementById(`error_${key}`);
                                    if (errorEl) errorEl.innerText = messages[0];
                                } else if (key === 'pemeriksaan_ids' || key.includes(
                                        'pemeriksaan_ids')) {
                                    // Khusus untuk error array checkbox
                                    document.getElementById('error_pemeriksaan_ids').innerText =
                                        messages[0];
                                }
                            }
                        } else {
                            document.getElementById('error_msg_skkh').innerText = error.message;
                            document.getElementById('error_global_skkh').classList.remove('d-none');
                        }
                    })
                    .finally(() => {
                        btnSimpanSkkh.innerHTML = originalText;
                        btnSimpanSkkh.disabled = false;
                    });
            });
        }

        // FUNGSI DETAIL PEMERIKSAAN
        function showDetail(id) {
            let modalDetail = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                'modalDetailPemeriksaan'));
            let loading = document.getElementById('loadingDetailSyariat');
            let konten = document.getElementById('kontenDetailSyariat');

            loading.classList.remove('d-none');
            konten.classList.add('d-none');
            modalDetail.show();

            fetch(`/syariat/pemeriksaan/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let pem = data.data;

                        // Set Info Hewan
                        document.getElementById('detail_eartag').innerText = pem.ternak
                            .nomor_eartag;
                        document.getElementById('detail_nama_panggilan').innerText = pem
                            .ternak.nama_panggilan || '-';

                        // Set Info Inspeksi
                        let dateObj = new Date(pem.tanggal_pemeriksaan);
                        document.getElementById('detail_tanggal').innerText = dateObj
                            .toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        document.getElementById('detail_petugas').innerText = pem
                            .penanggung_jawab ? pem.penanggung_jawab.name :
                            'Admin (Belum Login)';

                        // Set Banner Status Final
                        let banner = document.getElementById('detail_banner_status');
                        let teksStatus = document.getElementById('detail_teks_status');
                        let subStatus = document.getElementById('detail_sub_status');

                        if (pem.status === 'layak_qurban') {
                            banner.className =
                                'rounded p-3 mb-4 text-center border border-success bg-success bg-opacity-10 text-success';
                            teksStatus.innerHTML =
                                '<i class="bi bi-check-circle-fill me-2"></i> LAYAK QURBAN';
                            subStatus.innerText =
                                'Hewan telah memenuhi syarat syariat secara fisik.';
                        } else {
                            banner.className =
                                'rounded p-3 mb-4 text-center border border-danger bg-danger bg-opacity-10 text-danger';
                            teksStatus.innerHTML =
                                '<i class="bi bi-x-circle-fill me-2"></i> TIDAK LAYAK QURBAN';
                            subStatus.innerText =
                                'Ditemukan cacat fatal yang menggugurkan syarat syariat.';
                        }

                        // Render Tabel Kriteria
                        let tbody = document.getElementById('detail_tbody_kriteria');
                        tbody.innerHTML = '';

                        if (pem.detail_pemeriksaans && pem.detail_pemeriksaans.length > 0) {
                            pem.detail_pemeriksaans.forEach(detail => {
                                // Tentukan label Lolos/Cacat
                                let statusHtml = detail.is_lolos ?
                                    '<span class="badge bg-success rounded-pill w-100">Aman</span>' :
                                    '<span class="badge bg-danger rounded-pill w-100">Cacat</span>';

                                // Berikan highlight visual jika itu adalah cacat fatal
                                let isCacatFatal = (!detail.is_lolos && detail
                                    .kriteria.is_fatal);
                                  let rowClass = isCacatFatal ? 'table-danger' : '';
                                  let fotoHtml = detail.dir_bukti_cacat ?
                                      `<br><a href="/storage/${detail.dir_bukti_cacat}" target="_blank" class="btn btn-xs btn-outline-secondary mt-1 py-0" style="font-size: 0.7rem;"><i class="bi bi-camera"></i> Lihat Bukti</a>` :
                                      '';

                                  tbody.innerHTML += `
                              <tr class="${rowClass}">
                                  <td class="fw-semibold">
                                      ${detail.kriteria.nama_kriteria}
                                      ${isCacatFatal ? '<br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Menggugurkan Kelayakan</small>' : ''}
                                  </td>
                                  <td class="text-center">${statusHtml}</td>
                                  <td class="text-muted small">
                                      ${detail.catatan || '-'}
                                      ${fotoHtml}
                                  </td>
                              </tr>
                          `;
                            });
                        } else {
                            tbody.innerHTML =
                                `<tr><td colspan="3" class="text-center text-muted py-3">Rincian tidak ditemukan.</td></tr>`;
                        }

                        // Matikan Loading
                        loading.classList.add('d-none');
                        konten.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mengambil detail pemeriksaan.");
                    modalDetail.hide();
                });
        }

        // LOGIKA TOMBOL DETAIL
        if (container) { // container dari const container = document.getElementById('pemeriksaanContainer');
            container.addEventListener('click', function (e) {
                let btnDetail = e.target.closest('.btn-detail-pemeriksaan');

                if (btnDetail) {
                    let id = btnDetail.getAttribute('data-id');
                    showDetail(id);
                }
            });
        }

        // tombol hapus
        if (container) {
            container.addEventListener('click', function (e) {
                let btnDelete = e.target.closest('.btn-delete-pemeriksaan');

                if (btnDelete) {
                    let id = btnDelete.getAttribute('data-id');

                    if (confirm(
                            "Batalkan hasil pemeriksaan ini? Hewan akan dikembalikan ke status 'Belum Diperiksa'."
                        )) {
                        btnDelete.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        btnDelete.disabled = true;

                        fetch(`/syariat/pemeriksaan/${id}`, {
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
                                    alert(data.message);
                                    // Refresh halaman untuk memperbarui tabel dan daftar modal
                                    window.location.reload();
                                } else {
                                    alert(data.message);
                                    btnDelete.innerHTML = '<i class="bi bi-trash"></i>';
                                    btnDelete.disabled = false;
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert("Terjadi kesalahan sistem saat menghapus data.");
                                btnDelete.innerHTML = '<i class="bi bi-trash"></i>';
                                btnDelete.disabled = false;
                            });
                    }
                }
            });
        }

        // FUNGSI DETAIL SKKH
        function showDetailSkkh(id) {
            let modalDetail = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetailSKKH'));
            let loading = document.getElementById('loadingDetailSKKH');
            let konten = document.getElementById('kontenDetailSKKH');

            loading.classList.remove('d-none');
            konten.classList.add('d-none');
            modalDetail.show();

            fetch(`/syariat/skkh/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let skkh = data.data;

                        // Set Info SKKH
                        document.getElementById('skkh_detail_no_surat').innerText = skkh.nomor_surat || '-';
                        document.getElementById('skkh_detail_instansi').innerText = skkh.instansi_penerbit || '-';
                        document.getElementById('skkh_detail_dokter').innerText = skkh.nama_dokter_pemeriksa || '-';

                        let dateObj = new Date(skkh.tanggal_terbit);
                        document.getElementById('skkh_detail_tgl_terbit').innerText = dateObj.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                        // Set Download Button
                        let downloadBtn = document.getElementById('skkh_detail_download_btn');
                        if (downloadBtn) {
                            downloadBtn.setAttribute('href', `/storage/${skkh.dir_bukti_skkh}`);
                        }

                        // Render Tabel Ternak
                        let tbody = document.getElementById('skkh_detail_tbody_ternak');
                        tbody.innerHTML = '';

                        let pemeriksaanList = skkh.pemeriksaan_syariats || [];
                        document.getElementById('skkh_detail_count_ternak').innerText = pemeriksaanList.length;

                        if (pemeriksaanList.length > 0) {
                            pemeriksaanList.forEach(pem => {
                                let ternak = pem.ternak;
                                if (!ternak) return;

                                let statusHtml = pem.status === 'layak_qurban' ?
                                    '<span class="badge bg-success rounded-pill">Sah / Layak Qurban</span>' :
                                    '<span class="badge bg-danger rounded-pill">Cacat / Tidak Layak</span>';

                                let tipeJenis = ternak.ras && ternak.ras.tipe_ternak ? ternak.ras.tipe_ternak.nama_jenis : '-';
                                let rasName = ternak.ras ? ternak.ras.nama_ras : '-';
                                let kandangName = ternak.kandang ? ternak.kandang.nama_kandang : '-';

                                tbody.innerHTML += `
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">${ternak.nomor_eartag}</td>
                                        <td>${ternak.nama_panggilan || '-'}</td>
                                        <td>${tipeJenis} / ${rasName}</td>
                                        <td>${kandangName}</td>
                                        <td class="text-center pe-4">${statusHtml}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada hewan yang tertaut pada SKKH ini.</td></tr>`;
                        }

                        // Matikan Loading
                        loading.classList.add('d-none');
                        konten.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mengambil detail SKKH.");
                    modalDetail.hide();
                });
        }

        // LOGIKA TOMBOL DETAIL & HAPUS SKKH
        const skkhTableBody = document.getElementById('skkhTableBody');
        if (skkhTableBody) {
            skkhTableBody.addEventListener('click', function (e) {
                let btnDetail = e.target.closest('.btn-detail-skkh');
                let btnDelete = e.target.closest('.btn-delete-skkh');

                if (btnDetail) {
                    let id = btnDetail.getAttribute('data-id');
                    showDetailSkkh(id);
                } else if (btnDelete) {
                    let id = btnDelete.getAttribute('data-id');

                    if (confirm("Hapus dokumen SKKH ini? Hubungan dengan semua hewan yang ditautkan akan dilepas (dikembalikan ke status pemeriksaan tanpa SKKH).")) {
                        btnDelete.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        btnDelete.disabled = true;

                        fetch(`/syariat/skkh/${id}`, {
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
                                    alert(data.message);
                                    window.location.reload();
                                } else {
                                    alert(data.message);
                                    btnDelete.innerHTML = '<i class="bi bi-trash"></i>';
                                    btnDelete.disabled = false;
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert("Terjadi kesalahan sistem saat menghapus dokumen.");
                                btnDelete.innerHTML = '<i class="bi bi-trash"></i>';
                                btnDelete.disabled = false;
                            });
                    }
                }
            });
        }

        // ========== AJAX PAGINATION ==========
        const pemeriksaanContainer = document.getElementById('pemeriksaanContainer');
        const pemeriksaanPaginationContainer = document.getElementById('pemeriksaanPaginationContainer');
        const skkhTableBodyEl = document.getElementById('skkhTableBody');
        const skkhPaginationContainer = document.getElementById('skkhPaginationContainer');

        function fetchSyariatPage(urlStr) {
            const url = new URL(urlStr, window.location.origin);
            const isPemeriksaan = url.searchParams.has('pemeriksaan_page');
            const isSkkh = url.searchParams.has('skkh_page');

            if ((isPemeriksaan || (!isPemeriksaan && !isSkkh)) && pemeriksaanContainer) {
                pemeriksaanContainer.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted small mt-2 mb-0">Memuat data...</p>
                        </td>
                    </tr>`;
            }

            if ((isSkkh || (!isPemeriksaan && !isSkkh)) && skkhTableBodyEl) {
                skkhTableBodyEl.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted small mt-2 mb-0">Memuat data...</p>
                        </td>
                    </tr>`;
            }

            fetch(urlStr, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update Pemeriksaan
                    if (pemeriksaanContainer && data.pemeriksaanHtml !== undefined) {
                        pemeriksaanContainer.innerHTML = data.pemeriksaanHtml;
                    }
                    if (pemeriksaanPaginationContainer && data.pemeriksaanPagination !== undefined) {
                        pemeriksaanPaginationContainer.innerHTML = data.pemeriksaanPagination;
                    }

                    // Update SKKH
                    if (skkhTableBodyEl && data.skkhHtml !== undefined) {
                        skkhTableBodyEl.innerHTML = data.skkhHtml;
                    }
                    if (skkhPaginationContainer && data.skkhPagination !== undefined) {
                        skkhPaginationContainer.innerHTML = data.skkhPagination;
                    }

                    // Re-init tooltips
                    initTooltips();

                    // Update URL browser
                    history.pushState(null, '', urlStr);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Gagal memuat data halaman. Silakan coba lagi.");
            });
        }

        if (pemeriksaanPaginationContainer) {
            pemeriksaanPaginationContainer.addEventListener('click', function(e) {
                let link = e.target.closest('a');
                if (link && link.href) {
                    e.preventDefault();
                    fetchSyariatPage(link.href);
                }
            });
        }

        if (skkhPaginationContainer) {
            skkhPaginationContainer.addEventListener('click', function(e) {
                let link = e.target.closest('a');
                if (link && link.href) {
                    e.preventDefault();
                    fetchSyariatPage(link.href);
                }
            });
        }
        // ========== END AJAX PAGINATION ==========

        // Handle URL parameters for automatic actions on page load
        const urlParams = new URLSearchParams(window.location.search);
        const showPemeriksaanId = urlParams.get('show_pemeriksaan_id');
        if (showPemeriksaanId) {
            showDetail(showPemeriksaanId);
        }

        const tambahTernakId = urlParams.get('tambah_ternak_id');
        if (tambahTernakId) {
            let modalTambah = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahPemeriksaan'));
            if (modalTambah) {
                modalTambah.show();
                setTimeout(() => {
                    const cb = document.getElementById(`ternak_${tambahTernakId}`);
                    if (cb) {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                    }
                }, 300);
            }
        }

        if (showPemeriksaanId || tambahTernakId) {
            // Clean the query parameters from browser history
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
        }
    });
</script>
@endpush
