@push('scripts')
<script>
    // 1. FUNGSI GLOBAL: Template Tunggal untuk Card Sapi/Ternak
    // Ditaruh di objek 'window' agar bisa dipanggil dari file modal-tambah atau modal-edit mana pun
    window.renderTernakCardHTML = function (data, fotoUrl) {
        return `
            <div class="row g-0">
                <div class="col-md-8 col-lg-9">
                    <div class="card-body d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title fw-bold mb-0 text-primary">
                                    <i class="bi bi-tag-fill me-2"></i>Tag No: ${data.nomor_eartag}
                                </h4>
                                <div class="ms-3 container-nama-panggilan" id="nama-container-${data.id}">
                                    ${data.nama_panggilan ? `
                                        <span class="badge bg-light text-dark border rounded-pill trigger-nama" style="cursor: pointer; font-size: 0.85rem;" data-id="${data.id}" data-nama="${data.nama_panggilan}" data-bs-toggle="tooltip" data-bs-placement="top" title="Klik untuk ubah/hapus nama">
                                            "${data.nama_panggilan}" <i class="bi bi-pencil-square ms-1 text-muted"></i>
                                        </span>
                                    ` : `
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 trigger-nama" data-id="${data.id}" data-nama="" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah nama panggilan hewan">
                                            <i class="bi bi-plus"></i> Nama
                                        </button>
                                    `}
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2">Belum diperiksa</span>
                            </div>
                        </div>

                        <div class="row text-dark mb-4">
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Kategori Ternak</small>
                                <strong class="text-kategori">${data.ras.tipe_ternak.nama_jenis} | ${data.ras.nama_ras}</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Lokasi Kandang</small>
                                <strong class="text-kandang">${data.kandang.nama_kandang}</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Jenis Kelamin</small>
                                <strong class="text-gender text-capitalize">${data.jenis_kelamin}</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Berat Terakhir</small>
                                <strong class="fs-5 text-success text-berat">
                                    ${data.log_berats && data.log_berats.length > 0 ? data.log_berats[0].berat_kg + ' Kg' : 'Belum ditimbang'}
                                </strong>
                            </div>
                        </div>

                        <div class="mt-auto d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-secondary btn-sm btn-edit-ternak" 
                                data-id="${data.id}" 
                                data-eartag="${data.nomor_eartag}" 
                                data-ras="${data.ras_id}" 
                                data-kandang="${data.kandang_id}" 
                                data-gender="${data.jenis_kelamin}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-outline-info btn-sm btn-perkembangan-berat"
                                data-id="${data.id}" data-eartag="${data.nomor_eartag}">
                                <i class="bi bi-bar-chart-line"></i> Perkembangan Berat
                            </button>
                            <button class="btn btn-outline-warning btn-sm"><i class="bi bi-heart-pulse"></i> Data Kesehatan</button>
                            <button class="btn btn-outline-success btn-sm"><i class="bi bi-clipboard-check"></i> Kelayakan Kurban</button>
                            <button class="btn btn-outline-danger btn-sm ms-auto btn-delete-ternak" data-id="${data.id}"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 bg-white">
                    <img src="${fotoUrl}" class="img-fluid rounded-end h-100 w-100 object-fit-cover" alt="Foto Ternak ${data.nomor_eartag}" style="min-height: 250px;">
                </div>
            </div>
        `;
    };

    // 2. FUNGSI GLOBAL: Inisialisasi Tooltip & Update Counter Total
    window.initTooltips = function (context) {
        let triggers = context.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...triggers].map(el => {
            let instance = bootstrap.Tooltip.getInstance(el);
            if (instance) instance.dispose();
            new bootstrap.Tooltip(el);
        });
    };

    window.updateCounter = function (amount) {
        let badgeTotal = document.getElementById('totalEkorBadge');
        if (badgeTotal) {
            let currentNum = parseInt(badgeTotal.innerText.replace(/\D/g, '')) || 0;
            badgeTotal.innerText = `Total: ${currentNum + amount} Ekor`;
        }
    };

    // Jalankan tooltip saat halaman pertama kali dibuka
    document.addEventListener("DOMContentLoaded", function () {
        window.initTooltips(document);

        // ========== AJAX FILTER & SEARCH ==========
        const formFilter = document.getElementById('formFilter');
        const ternakContainer = document.getElementById('ternakContainer');
        const paginationContainer = document.getElementById('paginationContainer');
        const totalBadge = document.getElementById('totalEkorBadge');
        let searchTimer = null;

        // Fungsi utama: fetch data ternak via AJAX
        function fetchTernak(queryString) {
            // Tampilkan loading
            ternakContainer.innerHTML = `
                <div class="text-center py-5">
                    <span class="spinner-border text-primary" role="status"></span>
                    <p class="text-muted mt-2">Memuat data...</p>
                </div>`;

            fetch(`/ternak?${queryString}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Render card HTML dari server
                    if (data.html && data.html.trim() !== '') {
                        ternakContainer.innerHTML = data.html;
                    } else {
                        ternakContainer.innerHTML = `
                            <div class="alert alert-white text-center py-5 border-0 shadow-sm bg-white">
                                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                                <h5 class="text-muted">Tidak ada data ditemukan.</h5>
                                <p class="text-muted small">Coba ubah filter pencarian Anda.</p>
                            </div>`;
                    }

                    // Update pagination
                    paginationContainer.innerHTML = data.pagination || '';

                    // Update total badge
                    totalBadge.innerText = `Total: ${data.total} Ekor`;

                    // Re-init tooltips pada card baru
                    window.initTooltips(ternakContainer);

                    // Update URL di browser tanpa reload
                    const newUrl = `/ternak${queryString ? '?' + queryString : ''}`;
                    history.replaceState(null, '', newUrl);
                }
            })
            .catch(err => {
                ternakContainer.innerHTML = `
                    <div class="alert alert-danger text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Gagal memuat data. Silakan coba lagi.
                    </div>`;
            });
        }

        // Intercept form submit
        formFilter.addEventListener('submit', function(e) {
            e.preventDefault();
            let params = new URLSearchParams(new FormData(formFilter));
            // Hapus params yang kosong agar URL bersih
            for (let [key, val] of [...params.entries()]) {
                if (!val) params.delete(key);
            }
            fetchTernak(params.toString());
        });

        // Filter dropdown langsung tanpa klik tombol
        document.querySelectorAll('.filter-ternak').forEach(select => {
            select.addEventListener('change', function() {
                formFilter.dispatchEvent(new Event('submit'));
            });
        });

        // Debounced search saat mengetik
        const inputSearch = document.getElementById('inputSearchTernak');
        if (inputSearch) {
            inputSearch.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    formFilter.dispatchEvent(new Event('submit'));
                }, 400); // Tunggu 400ms setelah berhenti mengetik
            });
        }

        // Intercept pagination links (event delegation)
        paginationContainer.addEventListener('click', function(e) {
            let link = e.target.closest('a');
            if (link && link.href) {
                e.preventDefault();
                let url = new URL(link.href);
                fetchTernak(url.searchParams.toString());
            }
        });
        // ========== END AJAX FILTER & SEARCH ==========

        const containerTernak = document.getElementById('ternakContainer');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // 3. PUSAT EVENT DELEGATION: Menangkap semua klik di dalam list hewan
        containerTernak.addEventListener('click', function (e) {

            // A. Pemicu Tombol Edit
            let btnEdit = e.target.closest('.btn-edit-ternak');
            if (btnEdit) {
                document.getElementById('edit_ternak_id').value = btnEdit.getAttribute('data-id');
                document.getElementById('edit_nomor_eartag').value = btnEdit.getAttribute(
                'data-eartag');
                document.getElementById('edit_ras_id').value = btnEdit.getAttribute('data-ras');
                document.getElementById('edit_kandang_id').value = btnEdit.getAttribute('data-kandang');
                document.getElementById('edit_jenis_kelamin').value = btnEdit.getAttribute(
                    'data-gender');

                // Buka modal edit secara aman tanpa merusak backdrop
                let modalEdit = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                    'modalEditTernak'));
                modalEdit.show();
                return;
            }

            // B. Pemicu Tombol Delete
            let btnDelete = e.target.closest('.btn-delete-ternak');
            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');
                if (confirm("Apakah Anda yakin ingin menghapus data hewan ini?")) {
                    btnDelete.disabled = true;
                    fetch(`/ternak/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`card-ternak-${id}`).remove();
                                window.updateCounter(-1);
                                alert(data.message);
                            }
                        });
                }
            }
            // C. Pemicu Tombol Perkembangan Berat
            let btnBerat = e.target.closest('.btn-perkembangan-berat');
            if (btnBerat) {
                let id = btnBerat.getAttribute('data-id');
                let eartag = btnBerat.getAttribute('data-eartag');
                
                document.getElementById('berat_ternak_id').value = id;
                document.getElementById('label_eartag_berat').innerText = eartag;
                
                // Reset form & error
                document.getElementById('formTambahBerat').reset();
                document.getElementById('berat_global_error').classList.add('d-none');
                document.querySelectorAll('#formTambahBerat .is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('#formTambahBerat .invalid-feedback').forEach(el => el.innerText = '');
                
                // Show modal
                let modalBerat = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPerkembanganBerat'));
                modalBerat.show();
                
                // Load Data
                loadLogBerat(id);
            }
        });

        // 4. Fungsi memuat tabel log berat
        window.loadLogBerat = function(id) {
            let tbody = document.getElementById('tableBodyBerat');
            tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-muted"><span class="spinner-border spinner-border-sm" role="status"></span> Memuat data...</td></tr>';
            
            fetch(`/ternak/${id}/log-berat`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-muted">Belum ada data timbang.</td></tr>';
                    } else {
                        tbody.innerHTML = '';
                        data.data.forEach(log => {
                            // Format date
                            let d = new Date(log.tanggal_timbang);
                            let dateStr = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                            tbody.innerHTML += `
                                <tr>
                                    <td class="py-2 align-middle">${dateStr}</td>
                                    <td class="py-2 align-middle fw-bold text-success">${log.berat_kg} Kg</td>
                                </tr>
                            `;
                        });
                    }
                }
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-danger">Gagal memuat data.</td></tr>';
            });
        };

        // 5. Submit form tambah berat
        document.getElementById('formTambahBerat').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let id = document.getElementById('berat_ternak_id').value;
            let btnSubmit = document.getElementById('btnSimpanBerat');
            
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            let formData = new FormData(this);
            
            document.getElementById('berat_global_error').classList.add('d-none');
            document.querySelectorAll('#formTambahBerat .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            fetch(`/ternak/${id}/log-berat`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async res => {
                const isJson = res.headers.get('content-type')?.includes('application/json');
                if (!res.ok) {
                    if (res.status === 422 && isJson) {
                        const errData = await res.json();
                        return Promise.reject({ type: 'validation', errors: errData.errors });
                    }
                    let errorMsg = 'Terjadi kesalahan internal pada server.';
                    if (isJson) {
                        const errData = await res.json();
                        errorMsg = errData.message || errorMsg;
                    }
                    return Promise.reject({ type: 'server', message: errorMsg });
                }
                if (!isJson) return Promise.reject({ type: 'server', message: 'Sesi anda mungkin telah berakhir.' });
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('formTambahBerat').reset();
                    loadLogBerat(id);
                    
                    // Update the card UI berat terakhir
                    let card = document.getElementById(`card-ternak-${id}`);
                    if (card) {
                        let textBerat = card.querySelector('.text-berat');
                        if (textBerat) textBerat.innerText = data.data.berat_kg + ' Kg';
                    }
                }
            })
            .catch(err => {
                if (err.type === 'validation') {
                    for (const [key, messages] of Object.entries(err.errors || {})) {
                        let inputEl = document.getElementById(`input_${key}`);
                        let errorEl = document.getElementById(`error_${key}`);
                        if (inputEl) inputEl.classList.add('is-invalid');
                        if (errorEl) errorEl.innerText = messages[0];
                    }
                } else {
                    document.getElementById('berat_global_error_msg').innerText = err.message || 'Terjadi kesalahan.';
                    document.getElementById('berat_global_error').classList.remove('d-none');
                }
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-plus-lg"></i>';
            });
        });
    });

</script>
@endpush
