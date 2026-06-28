@push('scripts')
<script>
    // Unused AJAX helper functions removed.

    // 2. FUNGSI GLOBAL: Inisialisasi Tooltip
    window.initTooltips = function (context) {
        let triggers = context.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...triggers].map(el => {
            let instance = bootstrap.Tooltip.getInstance(el);
            if (instance) instance.dispose();
            new bootstrap.Tooltip(el);
        });
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
                let id = btnEdit.getAttribute('data-id');
                let currentKandangId = btnEdit.getAttribute('data-kandang');
                document.getElementById('edit_ternak_id').value = id;
                document.getElementById('formEditTernak').action = `/ternak/${id}`;
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
                        let deleteForm = document.getElementById('formDeleteTernak');
                        if (!deleteForm) {
                            deleteForm = document.createElement('form');
                            deleteForm.id = 'formDeleteTernak';
                            deleteForm.method = 'POST';
                            deleteForm.style.display = 'none';
                            deleteForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(deleteForm);
                        }
                        deleteForm.action = `/ternak/${id}`;
                        deleteForm.submit();
                    }
                });
                return;
            }
            // C. Pemicu Tombol Perkembangan Berat
            let btnBerat = e.target.closest('.btn-perkembangan-berat');
            if (btnBerat) {
                let id = btnBerat.getAttribute('data-id');
                let eartag = btnBerat.getAttribute('data-eartag');
                
                document.getElementById('berat_ternak_id').value = id;
                document.getElementById('formTambahBerat').action = `/ternak/${id}/log-berat`;
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
        // Form submits normally via standard HTTP POST, JS AJAX submission removed.
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
