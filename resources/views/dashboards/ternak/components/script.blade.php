@push('scripts')
<script>
    // 1. FUNGSI GLOBAL: Template Tunggal untuk Card Sapi/Ternak
    // Ditaruh di objek 'window' agar bisa dipanggil dari file modal-tambah atau modal-edit mana pun
    window.renderTernakCardHTML = function (data, fotoUrl) {
        const isKandangPenuh = data.kandang && (data.kandang.ternaks_count ?? 0) >= (data.kandang.kapasitas_maksimal ?? 0);
        const hasFoto = data.dir_foto_hewan ? true : false;
        
        // Health status evaluation
        const hasLog = (data.log_kesehatans_count ?? 0) > 0;
        let healthStatusHTML = '';
        if (!hasLog) {
            healthStatusHTML = `<span class="badge bg-warning rounded-pill px-3 py-2"><span class="badge-text-full">Belum diperiksa</span><span class="badge-text-compact">Belum Cek</span></span>`;
        } else if (data.is_karantina) {
            healthStatusHTML = `<span class="badge bg-danger rounded-pill px-3 py-2"><span class="badge-text-full">Di Karantina</span><span class="badge-text-compact">Karantina</span></span>`;
        } else {
            healthStatusHTML = `<span class="badge bg-success rounded-pill px-3 py-2"><span class="badge-text-full">Tersedia</span><span class="badge-text-compact">Tersedia</span></span>`;
        }

        // Qurban evaluation
        const syariats = data.pemeriksaan_syariat || [];
        const latestSyariat = syariats.length > 0 ? [...syariats].sort((a,b) => b.id - a.id)[0] : null;
        const isLayak = latestSyariat && latestSyariat.status === 'layak_qurban';
        const hasSkkh = latestSyariat && latestSyariat.dokumen_skkh_id ? true : false;

        let qurbanStatusHTML = '';
        if (!latestSyariat) {
            qurbanStatusHTML = `<span class="badge bg-warning rounded-pill px-3 py-2"><span class="badge-text-full">Belum dicek</span><span class="badge-text-compact">Belum Cek</span></span>`;
        } else if (isLayak) {
            qurbanStatusHTML = `<span class="badge bg-success rounded-pill px-3 py-2"><span class="badge-text-full">Layak Qurban</span><span class="badge-text-compact">Layak</span></span>`;
        } else {
            qurbanStatusHTML = `<span class="badge bg-danger rounded-pill px-3 py-2"><span class="badge-text-full">Tidak Layak</span><span class="badge-text-compact">T. Layak</span></span>`;
        }

        const skkhHTML = hasSkkh ? `
            <span class="badge bg-info text-white rounded-pill px-2 py-1" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi SKKH">
                <i class="bi bi-check-lg"></i>
            </span>
        ` : '';

        const rightColumnHTML = hasFoto ? `
            <div class="col-md-4 col-lg-4 p-0">
                <img src="${fotoUrl}" class="img-fluid h-100 w-100 object-fit-cover" alt="Foto Ternak ${data.nomor_eartag}" style="min-height: 250px;">
            </div>
        ` : `
            <div class="col-md-4 col-lg-4 p-0">
                <img src="${fotoUrl}" class="img-fluid h-100 w-100 object-fit-cover" alt="Foto Ternak ${data.nomor_eartag}" style="min-height: 250px;">
            </div>
        `;
        return `
            <div class="row g-0">
                <div class="col-md-8 col-lg-8">
                    <div class="card-body d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title fw-bold mb-0 text-primary">
                                    <i class="bi bi-tag-fill me-2"></i><span class="btn-text-responsive">Tag No: </span>${data.nomor_eartag}
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
                            <div class="d-flex align-items-center gap-2">
                                ${qurbanStatusHTML}
                                ${healthStatusHTML}
                                ${skkhHTML}
                            </div>
                        </div>

                        <div class="row text-dark mb-4">
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Kategori Ternak</small>
                                <strong class="text-kategori">${data.ras.tipe_ternak.nama_jenis} | ${data.ras.nama_ras}</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Lokasi Kandang</small>
                                <strong class="text-kandang ${isKandangPenuh ? 'text-danger' : ''}">${data.kandang.nama_kandang}</strong>
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
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Asal Usul</small>
                                <strong class="text-asal">${Number(data.harga_beli_awal) > 0 ? 'Pembelian' : 'Lahir di Peternakan'}</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Usia</small>
                                <strong class="text-usia">${data.umur_bulan} Bulan</strong>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">${Number(data.harga_beli_awal) > 0 ? 'Harga Beli' : 'Tanggal Lahir'}</small>
                                <strong class="${Number(data.harga_beli_awal) > 0 ? 'text-primary' : ''}">
                                    ${Number(data.harga_beli_awal) > 0 ? 'Rp ' + Number(data.harga_beli_awal).toLocaleString('id-ID') : (data.tanggal_lahir ? new Date(data.tanggal_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-')}
                                </strong>
                            </div>
                        </div>

                        <div class="mt-auto d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-secondary btn-sm btn-edit-ternak" 
                                data-id="${data.id}" 
                                data-eartag="${data.nomor_eartag}" 
                                data-ras="${data.ras_id}" 
                                data-kandang="${data.kandang_id}" 
                                data-gender="${data.jenis_kelamin}"
                                data-foto="${data.dir_foto_hewan ? window.storageBaseUrl + '/' + data.dir_foto_hewan : ''}"
                                data-harga-beli="${data.harga_beli_awal || ''}"
                                data-tanggal-lahir="${data.tanggal_lahir ? (typeof data.tanggal_lahir === 'string' ? data.tanggal_lahir.substring(0, 10) : new Date(data.tanggal_lahir).toISOString().substring(0,10)) : ''}"
                                data-umur-bulan="${data.umur_bulan}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Ternak">
                                <i class="bi bi-pencil"></i><span class="btn-text-responsive ms-1">Edit</span>
                            </button>
                            <button class="btn btn-outline-info btn-sm btn-perkembangan-berat"
                                data-id="${data.id}" data-eartag="${data.nomor_eartag}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Perkembangan Berat">
                                <i class="bi bi-bar-chart-line"></i><span class="btn-text-responsive ms-1">Perkembangan Berat</span>
                            </button>
                            <a href="/kesehatan?tambah_ternak_id=${data.id}" class="btn btn-outline-warning btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Data Kesehatan">
                                <i class="bi bi-heart-pulse"></i><span class="btn-text-responsive ms-1">Data Kesehatan</span>
                            </a>
                            ${latestSyariat ? `
                                <a href="/syariat?show_pemeriksaan_id=${latestSyariat.id}" class="btn btn-outline-success btn-sm"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Kelayakan Kurban">
                                    <i class="bi bi-clipboard-pulse"></i><span class="btn-text-responsive ms-1">Kelayakan Kurban</span>
                                </a>
                            ` : `
                                <a href="/syariat?tambah_ternak_id=${data.id}" class="btn btn-outline-success btn-sm"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Kelayakan Kurban">
                                    <i class="bi bi-clipboard-pulse"></i><span class="btn-text-responsive ms-1">Kelayakan Kurban</span>
                                </a>
                            `}
                            @if(Auth::user()->role === 'owner/admin')
                            <button class="btn btn-outline-primary btn-sm btn-keuangan-ternak" data-id="${data.id}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kartu Rapor Keuangan">
                                <i class="bi bi-receipt"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm ms-auto btn-delete-ternak" data-id="${data.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Ternak">
                                <i class="bi bi-trash"></i><span class="btn-text-responsive ms-1">Hapus</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                ${rightColumnHTML}
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

    window.updateKandangCapacity = function (kandangId, newCount, maxVal = null) {
        // Tambah modal
        let selectTambah = document.querySelector('#modalTambahTernak select[name="kandang_id"]');
        if (selectTambah) {
            let option = selectTambah.querySelector(`option[value="${kandangId}"]`);
            if (option) {
                let max = maxVal !== null ? maxVal : (parseInt(option.getAttribute('data-max')) || 0);
                let baseNama = option.getAttribute('data-nama') || option.text.split(' (')[0];
                
                option.setAttribute('data-count', newCount);
                option.setAttribute('data-max', max);
                option.setAttribute('data-nama', baseNama);
                
                if (newCount >= max) {
                    option.disabled = true;
                    option.text = `${baseNama} (${newCount}/${max}) - [Penuh]`;
                } else {
                    option.disabled = false;
                    option.text = `${baseNama} (${newCount}/${max})`;
                }
            }
        }
        
        // Edit modal
        let selectEdit = document.querySelector('#modalEditTernak select[name="kandang_id"]');
        if (selectEdit) {
            let option = selectEdit.querySelector(`option[value="${kandangId}"]`);
            if (option) {
                let max = maxVal !== null ? maxVal : (parseInt(option.getAttribute('data-max')) || 0);
                let baseNama = option.getAttribute('data-nama') || option.text;
                
                option.setAttribute('data-count', newCount);
                option.setAttribute('data-max', max);
                option.setAttribute('data-nama', baseNama);
            }
        }
    };

    window.decrementKandangCount = function (kandangId) {
        let selectEdit = document.querySelector('#modalEditTernak select[name="kandang_id"]');
        if (selectEdit) {
            let option = selectEdit.querySelector(`option[value="${kandangId}"]`);
            if (option) {
                let currentCount = parseInt(option.getAttribute('data-count')) || 0;
                let newCount = Math.max(0, currentCount - 1);
                window.updateKandangCapacity(kandangId, newCount);
            }
        }
    };

    // Jalankan tooltip saat halaman pertama kali dibuka
    document.addEventListener("DOMContentLoaded", function () {
        window.initTooltips(document);

        // Initialize Select2 on search filters
        $('#filterKandang').select2({
            width: '100%'
        });
        $('#filterRas').select2({
            width: '100%'
        });

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
            let formData = new FormData(formFilter);

            // Append header filters
            const sortVal = document.getElementById('filterSort').value;
            const startDateVal = document.getElementById('filterStartDate').value;
            const endDateVal = document.getElementById('filterEndDate').value;
            
            if (sortVal) formData.append('sort', sortVal);
            if (startDateVal) formData.append('start_date', startDateVal);
            if (endDateVal) formData.append('end_date', endDateVal);

            let params = new URLSearchParams(formData);
            // Hapus params yang kosong agar URL bersih
            for (let [key, val] of [...params.entries()]) {
                if (!val) params.delete(key);
            }
            fetchTernak(params.toString());
        });

        // Filter dropdown langsung tanpa klik tombol (menggunakan jQuery agar ter-trigger dari Select2)
        $('.filter-ternak').on('change', function() {
            formFilter.dispatchEvent(new Event('submit'));
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
                let currentKandangId = btnEdit.getAttribute('data-kandang');
                document.getElementById('edit_ternak_id').value = btnEdit.getAttribute('data-id');
                document.getElementById('edit_nomor_eartag').value = btnEdit.getAttribute(
                'data-eartag');
                
                // Set native values and trigger change for Select2
                document.getElementById('edit_ras_id').value = btnEdit.getAttribute('data-ras');
                document.getElementById('edit_kandang_id').value = currentKandangId;
                $('#edit_ras_id').trigger('change');
                $('#edit_kandang_id').trigger('change');

                 document.getElementById('edit_jenis_kelamin').value = btnEdit.getAttribute(
                     'data-gender');
 
                  // Populate Asal Usul fields
                  let price = btnEdit.getAttribute('data-harga-beli');
                  let dob = btnEdit.getAttribute('data-tanggal-lahir');
                  let umurBulan = btnEdit.getAttribute('data-umur-bulan');
  
                  let editRadioAsalBeli = document.getElementById('edit_asal_beli');
                  let editRadioAsalLahir = document.getElementById('edit_asal_lahir');
                  let editInputHargaBeli = document.getElementById('edit_harga_beli_awal');
                  let editInputTanggalLahir = document.getElementById('edit_tanggal_lahir');
                  let editInputUmurBeli = document.getElementById('edit_umur_bulan_beli');
  
                  if (price && Number(price) > 0) {
                      if (editRadioAsalBeli) editRadioAsalBeli.checked = true;
                      if (editInputHargaBeli) editInputHargaBeli.value = price;
                      if (editInputUmurBeli) editInputUmurBeli.value = umurBulan;
                  } else if (dob) {
                      if (editRadioAsalLahir) editRadioAsalLahir.checked = true;
                      if (editInputTanggalLahir) editInputTanggalLahir.value = dob;
                  } else {
                      if (editRadioAsalBeli) editRadioAsalBeli.checked = true;
                  }
                 
                 if (typeof window.editToggleAsalUsul === 'function') {
                     window.editToggleAsalUsul();
                 }

                // Populate current photo preview if exists
                let currentFotoUrl = btnEdit.getAttribute('data-foto');
                let currentFotoContainer = document.getElementById('edit_current_foto_container');
                let currentFotoImg = document.getElementById('edit_current_foto');
                if (currentFotoUrl) {
                    if (currentFotoImg) currentFotoImg.src = currentFotoUrl;
                    if (currentFotoContainer) currentFotoContainer.classList.remove('d-none');
                } else {
                    if (currentFotoImg) currentFotoImg.src = '';
                    if (currentFotoContainer) currentFotoContainer.classList.add('d-none');
                }
                
                // Clear any previous edit new photo previews/errors
                let inputFotoEdit = document.getElementById('edit_foto');
                if (inputFotoEdit) {
                    inputFotoEdit.value = '';
                    inputFotoEdit.classList.remove('is-invalid');
                }
                let newFotoContainer = document.getElementById('edit_new_foto_preview_container');
                if (newFotoContainer) newFotoContainer.classList.add('d-none');
                let newFotoImg = document.getElementById('edit_new_foto_preview');
                if (newFotoImg) newFotoImg.src = '';
                let errorFotoEdit = document.getElementById('error_edit_foto');
                if (errorFotoEdit) errorFotoEdit.innerText = '';

                // Reset Select2 validation states
                $('#edit_ras_id').removeClass('is-invalid');
                $('#edit_kandang_id').removeClass('is-invalid');

                // Update option states and text dynamically
                let selectKandang = document.getElementById('edit_kandang_id');
                if (selectKandang) {
                    let options = selectKandang.options;
                    for (let i = 0; i < options.length; i++) {
                        let option = options[i];
                        let count = parseInt(option.getAttribute('data-count')) || 0;
                        let max = parseInt(option.getAttribute('data-max')) || 0;
                        let baseNama = option.getAttribute('data-nama') || option.text;
                        
                        if (option.value == currentKandangId) {
                            option.disabled = false;
                            option.text = `${baseNama} (${count}/${max})`;
                        } else {
                            if (count >= max) {
                                option.disabled = true;
                                option.text = `${baseNama} (${count}/${max}) - [Penuh]`;
                            } else {
                                option.disabled = false;
                                option.text = `${baseNama} (${count}/${max})`;
                            }
                        }
                    }
                }

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
                let kandangId = null;
                let card = document.getElementById(`card-ternak-${id}`);
                if (card) {
                    let btnEdit = card.querySelector('.btn-edit-ternak');
                    if (btnEdit) {
                        kandangId = btnEdit.getAttribute('data-kandang');
                    }
                }
                Swal.fire({
                    title: 'Hapus Ternak?',
                    text: "Apakah Anda yakin ingin menghapus data hewan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                    if (kandangId) {
                                        window.decrementKandangCount(kandangId);
                                    }
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#428475'
                                    });
                                }
                            });
                    }
                });
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

        window.currentLogs = [];
        window.currentLogsPage = 1;
        const logsPerPage = 5;

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
                    window.currentLogs = data.data || [];
                    window.renderLogsPage(1);
                }
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-danger">Gagal memuat data.</td></tr>';
            });
        };

        window.renderLogsPage = function(page) {
            window.currentLogsPage = page;
            let tbody = document.getElementById('tableBodyBerat');
            if (!tbody) return;

            if (!window.currentLogs || window.currentLogs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-muted">Belum ada data timbang.</td></tr>';
                window.renderLogsPagination(0);
                return;
            }

            tbody.innerHTML = '';
            let start = (page - 1) * logsPerPage;
            let end = start + logsPerPage;
            let paginated = window.currentLogs.slice(start, end);

            paginated.forEach(log => {
                let d = new Date(log.tanggal_timbang);
                let dateStr = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                tbody.innerHTML += `
                    <tr>
                        <td class="py-2 align-middle">${dateStr}</td>
                        <td class="py-2 align-middle fw-bold text-success">${log.berat_kg} Kg</td>
                    </tr>
                `;
            });

            window.renderLogsPagination(window.currentLogs.length);
        };

        window.renderLogsPagination = function(totalItems) {
            let container = document.getElementById('paginationBerat');
            if (!container) return;

            let totalPages = Math.ceil(totalItems / logsPerPage);
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination pagination-sm mb-0 gap-1">';
            
            // Prev button
            html += `
                <li class="page-item ${window.currentLogsPage === 1 ? 'disabled' : ''}">
                    <a class="page-link rounded-2" href="#" onclick="event.preventDefault(); window.renderLogsPage(${window.currentLogsPage - 1})">&laquo;</a>
                </li>
            `;

            for (let i = 1; i <= totalPages; i++) {
                html += `
                    <li class="page-item ${window.currentLogsPage === i ? 'active' : ''}">
                        <a class="page-link rounded-2" href="#" onclick="event.preventDefault(); window.renderLogsPage(${i})">${i}</a>
                    </li>
                `;
            }

            // Next button
            html += `
                <li class="page-item ${window.currentLogsPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link rounded-2" href="#" onclick="event.preventDefault(); window.renderLogsPage(${window.currentLogsPage + 1})">&raquo;</a>
                </li>
            `;

            html += '</ul>';
            container.innerHTML = html;
        };

        // 5. Submit form tambah berat
        document.getElementById('formTambahBerat').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let id = document.getElementById('berat_ternak_id').value;
            let btnSubmit = document.getElementById('btnSimpanBerat');
            let inputBeratKg = document.getElementById('input_berat_kg');
            
            document.getElementById('berat_global_error').classList.add('d-none');
            document.querySelectorAll('#formTambahBerat .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            if (inputBeratKg) {
                if (!inputBeratKg.value.trim()) {
                    let errEl = document.getElementById('error_berat_kg');
                    if (errEl) errEl.innerText = 'Berat badan wajib diisi.';
                    inputBeratKg.classList.add('is-invalid');
                    return;
                }
                if (parseFloat(inputBeratKg.value) < 1) {
                    let errEl = document.getElementById('error_berat_kg');
                    if (errEl) errEl.innerText = 'Berat badan harus minimal 1 Kg.';
                    inputBeratKg.classList.add('is-invalid');
                    return;
                }
            }
            
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            let formData = new FormData(this);
            
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
        // ========== KARTU RAPOR KEUANGAN ==========
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-keuangan-ternak')) {
                let btn = e.target.closest('.btn-keuangan-ternak');
                let id = btn.getAttribute('data-id');
                
                let modalEl = document.getElementById('modalKeuangan');
                let modal = new bootstrap.Modal(modalEl);
                modal.show();

                document.getElementById('keuanganLoading').classList.remove('d-none');
                document.getElementById('keuanganContent').classList.add('d-none');
                
                fetch(`/ternak/${id}/keuangan`, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        let data = res.data;
                        
                        // Extract eartag from edit button on the same card
                        let eartagEl = btn.closest('.card').querySelector('.btn-edit-ternak');
                        document.getElementById('kEartag').innerText = eartagEl ? eartagEl.getAttribute('data-eartag') : '-';
                        
                        const formatRp = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
                        
                        document.getElementById('kModalAwal').innerText = formatRp(data.modal_awal);
                        document.getElementById('kBiayaPakan').innerText = formatRp(data.biaya_pakan_proporsional);
                        document.getElementById('kBiayaMedis').innerText = formatRp(data.biaya_medis);
                        document.getElementById('kTotalRiil').innerText = formatRp(data.total_modal);
                        document.getElementById('kSaranJual').innerText = formatRp(data.saran_jual);
                        
                        // Render Rincian
                        let rincianContainer = document.getElementById('kRincianContainer');
                        if (data.rincian_bulanan && data.rincian_bulanan.length > 0) {
                            let html = '';
                            data.rincian_bulanan.forEach(item => {
                                html += `
                                <div class="mb-2 pb-1" style="border-bottom: 1px dashed #eee;">
                                    <div class="fw-bold text-dark" style="font-size: 0.75rem;">${item.bulan_tahun}</div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                        <span>Pakan:</span> <span>${formatRp(item.biaya_pakan)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                        <span>Medis:</span> <span>${formatRp(item.biaya_medis)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-semibold" style="font-size: 0.75rem;">
                                        <span>Subtotal:</span> <span>${formatRp(item.subtotal)}</span>
                                    </div>
                                </div>
                                `;
                            });
                            rincianContainer.innerHTML = html;
                        } else {
                            rincianContainer.innerHTML = '<div class="text-center text-muted py-2" style="font-size: 0.75rem;">Belum ada rincian biaya berjalan.</div>';
                        }

                        document.getElementById('keuanganLoading').classList.add('d-none');
                        document.getElementById('keuanganContent').classList.remove('d-none');
                    } else {
                        alert(res.message || 'Gagal memuat data keuangan');
                        modal.hide();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat memuat data keuangan.');
                    modal.hide();
                });
            }
        });

    });

</script>
@endpush
