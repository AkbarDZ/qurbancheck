<div class="modal fade" id="modalEditTernak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="formEditTernak" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-light border-bottom-0">
                    <h1 class="modal-title fs-5 fw-bold text-dark">
                        <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Data Ternak
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    
                    <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="edit_global_error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="edit_global_error_msg"></span>
                    </div>

                    <input type="hidden" id="edit_ternak_id" name="id">
                    
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">No. Eartag</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nomor_eartag" id="edit_nomor_eartag" required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnGenerateEartagEdit" title="Generate ulang nomor eartag">
                                            <i class="bi bi-magic"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nomor_eartag" style="font-size: 0.75rem;"></div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">
                                        Ketik manual atau generate otomatis. <span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> Nomor eartag harus unik (tidak boleh sama).</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Ras Ternak</label>
                                    <select class="form-select select2-edit-ras" name="ras_id" id="edit_ras_id" required>
                                        @foreach($rasTernaks as $ras)
                                            <option value="{{ $ras->id }}">{{ $ras->tipeTernak->nama_jenis }} - {{ $ras->nama_ras }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_ras_id" style="font-size: 0.75rem;"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                                    <select class="form-select" name="jenis_kelamin" id="edit_jenis_kelamin" required>
                                        <option value="jantan">Jantan</option>
                                        <option value="betina">Betina</option>
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_jenis_kelamin" style="font-size: 0.75rem;"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Kandang</label>
                                    <select class="form-select select2-edit-kandang" name="kandang_id" id="edit_kandang_id" required>
                                        @foreach($kandangs as $kandang)
                                            <option value="{{ $kandang->id }}"
                                                data-count="{{ $kandang->ternaks_count }}"
                                                data-max="{{ $kandang->kapasitas_maksimal }}"
                                                data-nama="{{ $kandang->nama_kandang }}">
                                                {{ $kandang->nama_kandang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_kandang_id" style="font-size: 0.75rem;"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Asal Usul Hewan</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="edit_asal_beli" value="beli" checked>
                                            <label class="form-check-label small" for="edit_asal_beli">Beli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="edit_asal_lahir" value="lahir">
                                            <label class="form-check-label small" for="edit_asal_lahir">Lahir di Peternakan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="edit_container_harga_beli">
                                    <label class="form-label small fw-bold text-muted">Harga Beli Awal (Rp)</label>
                                    <input type="number" step="0.01" class="form-control" name="harga_beli_awal" id="edit_harga_beli_awal" placeholder="Contoh: 15000000">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_harga_beli_awal" style="font-size: 0.75rem;"></div>
                                </div>
                                <div class="col-md-6 mb-3 d-none" id="edit_container_tanggal_lahir">
                                    <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir" id="edit_tanggal_lahir">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_tanggal_lahir" style="font-size: 0.75rem;"></div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label class="form-label small fw-bold text-muted">Ganti Foto Ternak <span class="text-secondary fw-normal">(Kosongkan jika tidak diubah)</span></label>
                                    <input type="file" class="form-control" name="foto" id="edit_foto" accept="image/*">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_foto" style="font-size: 0.75rem;"></div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">
                                        Format gambar (JPG, PNG, WEBP). <span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> Ukuran foto maksimal 2MB.</span>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <!-- Current Photo Preview -->
                                        <div class="col-sm-6 d-none" id="edit_current_foto_container">
                                            <label class="form-label small text-muted fw-bold d-block">Foto Saat Ini:</label>
                                            <div class="d-inline-block rounded border overflow-hidden bg-light shadow-sm" style="width: 150px; height: 112px;">
                                                <img src="" id="edit_current_foto" class="w-100 h-100 object-fit-cover">
                                            </div>
                                        </div>
                                        
                                        <!-- New Photo Preview -->
                                        <div class="col-sm-6 d-none" id="edit_new_foto_preview_container">
                                            <label class="form-label small text-muted fw-bold d-block">Preview Foto Baru:</label>
                                            <div class="position-relative d-inline-block rounded border overflow-hidden bg-light shadow-sm" style="width: 150px; height: 112px; border: 2px dashed #dee2e6 !important;">
                                                <img src="" id="edit_new_foto_preview" class="w-100 h-100 object-fit-cover">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 py-0 px-1" id="btnRemoveFotoEdit" style="font-size: 0.75rem; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-3">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-3" id="btnUpdateTernak">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const formEditTernak = document.getElementById('formEditTernak');
    const btnUpdate = document.getElementById('btnUpdateTernak');
    const btnGenerateEartagEdit = document.getElementById('btnGenerateEartagEdit');

    // Toggle Asal Usul Hewan (Mutually Exclusive) - Edit Form
    const editRadioAsalBeli = document.getElementById('edit_asal_beli');
    const editRadioAsalLahir = document.getElementById('edit_asal_lahir');
    const editContainerHargaBeli = document.getElementById('edit_container_harga_beli');
    const editContainerTanggalLahir = document.getElementById('edit_container_tanggal_lahir');
    const editInputHargaBeli = document.getElementById('edit_harga_beli_awal');
    const editInputTanggalLahir = document.getElementById('edit_tanggal_lahir');

    window.editToggleAsalUsul = function() {
        if (editRadioAsalBeli && editRadioAsalBeli.checked) {
            if (editContainerHargaBeli) editContainerHargaBeli.classList.remove('d-none');
            if (editInputHargaBeli) {
                editInputHargaBeli.disabled = false;
                editInputHargaBeli.required = true;
                if (editInputHargaBeli.value === '0') {
                    editInputHargaBeli.value = '';
                }
            }
            if (editContainerTanggalLahir) editContainerTanggalLahir.classList.add('d-none');
            if (editInputTanggalLahir) {
                editInputTanggalLahir.disabled = true;
                editInputTanggalLahir.required = false;
                editInputTanggalLahir.value = '';
            }
        } else {
            if (editContainerHargaBeli) editContainerHargaBeli.classList.add('d-none');
            if (editInputHargaBeli) {
                editInputHargaBeli.disabled = true;
                editInputHargaBeli.required = false;
                editInputHargaBeli.value = '0';
            }
            if (editContainerTanggalLahir) editContainerTanggalLahir.classList.remove('d-none');
            if (editInputTanggalLahir) {
                editInputTanggalLahir.disabled = false;
                editInputTanggalLahir.required = true;
            }
        }
    }

    if (editRadioAsalBeli && editRadioAsalLahir) {
        editRadioAsalBeli.addEventListener('change', window.editToggleAsalUsul);
        editRadioAsalLahir.addEventListener('change', window.editToggleAsalUsul);
        window.editToggleAsalUsul();
    }

    if (formEditTernak) {
        formEditTernak.addEventListener('reset', function() {
            setTimeout(window.editToggleAsalUsul, 10);
        });
    }
    
    const globalErrorAlert = document.getElementById('edit_global_error');
    const globalErrorMsg = document.getElementById('edit_global_error_msg');
    
    // Pastikan token CSRF tersedia
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    // Initialize Select2 when modal is shown
    $('#modalEditTernak').on('shown.bs.modal', function () {
        $('.select2-edit-ras', this).select2({
            dropdownParent: $('#modalEditTernak'),
            width: '100%'
        });
        $('.select2-edit-kandang', this).select2({
            dropdownParent: $('#modalEditTernak'),
            width: '100%'
        });
    });

    // === Preview Foto Baru & Validasi Ukuran (2MB) ===
    const fileInputEdit = document.getElementById('edit_foto');
    const previewContainerEdit = document.getElementById('edit_new_foto_preview_container');
    const previewImgEdit = document.getElementById('edit_new_foto_preview');
    const btnRemoveEdit = document.getElementById('btnRemoveFotoEdit');
    const errorFotoEdit = document.getElementById('error_edit_foto');

    if (fileInputEdit) {
        fileInputEdit.addEventListener('change', function () {
            const file = this.files[0];
            fileInputEdit.classList.remove('is-invalid');
            if (errorFotoEdit) errorFotoEdit.innerText = '';
            
            if (file) {
                // Check size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    fileInputEdit.classList.add('is-invalid');
                    if (errorFotoEdit) errorFotoEdit.innerText = 'Ukuran foto melebihi 2MB. Silakan pilih file yang lebih kecil.';
                    fileInputEdit.value = ''; // Reset input
                    if (previewContainerEdit) previewContainerEdit.classList.add('d-none');
                    if (previewImgEdit) previewImgEdit.src = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImgEdit) previewImgEdit.src = e.target.result;
                    if (previewContainerEdit) previewContainerEdit.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                if (previewContainerEdit) previewContainerEdit.classList.add('d-none');
                if (previewImgEdit) previewImgEdit.src = '';
            }
        });
    }

    if (btnRemoveEdit) {
        btnRemoveEdit.addEventListener('click', function () {
            if (fileInputEdit) fileInputEdit.value = '';
            if (previewContainerEdit) previewContainerEdit.classList.add('d-none');
            if (previewImgEdit) previewImgEdit.src = '';
            if (fileInputEdit) fileInputEdit.classList.remove('is-invalid');
            if (errorFotoEdit) errorFotoEdit.innerText = '';
        });
    }

    // ==================================================
    // 1. LOGIKA TOMBOL SIHIR (GENERATE EARTAG)
    // ==================================================
    if (btnGenerateEartagEdit) {
        btnGenerateEartagEdit.addEventListener('click', function() {
            let date = new Date();
            let year = date.getFullYear().toString().slice(-2);
            let month = ('0' + (date.getMonth() + 1)).slice(-2);
            let randomNum = Math.floor(1000 + Math.random() * 9000);
            
            let inputTag = document.getElementById('edit_nomor_eartag');
            inputTag.value = `QRBN-${year}${month}-${randomNum}`;
            
            // Bersihkan indikator error merah jika kodenya diganti baru
            inputTag.classList.remove('is-invalid');
            document.getElementById('error_edit_nomor_eartag').innerText = '';
        });
    }

    // ==================================================
    // 2. LOGIKA AJAX SUBMIT FORM EDIT
    // ==================================================
    if (formEditTernak) {
        formEditTernak.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah mutlak halaman me-refresh!
            
            let id = document.getElementById('edit_ternak_id').value;
            if(!id) {
                globalErrorMsg.innerText = "ID Hewan tidak ditemukan. Silakan close modal dan klik edit kembali.";
                globalErrorAlert.classList.remove('d-none');
                return;
            }

            // Reset seluruh visual error lama
            globalErrorAlert.classList.add('d-none');
            document.querySelectorAll('#formEditTernak .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#formEditTernak .invalid-feedback').forEach(el => el.innerText = '');

            // Validation before submit
            if (editRadioAsalBeli && editRadioAsalBeli.checked) {
                if (editInputHargaBeli && !editInputHargaBeli.value.trim()) {
                    let errEl = document.getElementById('error_edit_harga_beli_awal');
                    if (errEl) errEl.innerText = 'Harga beli awal wajib diisi jika asal usul adalah Beli.';
                    if (editInputHargaBeli) editInputHargaBeli.classList.add('is-invalid');
                    return;
                }
            } else if (editRadioAsalLahir && editRadioAsalLahir.checked) {
                if (editInputTanggalLahir && !editInputTanggalLahir.value) {
                    let errEl = document.getElementById('error_edit_tanggal_lahir');
                    if (errEl) errEl.innerText = 'Tanggal lahir wajib diisi jika lahir di peternakan.';
                    if (editInputTanggalLahir) editInputTanggalLahir.classList.add('is-invalid');
                    return;
                }
            }

            let oldKandangId = null;
            let card = document.getElementById(`card-ternak-${id}`);
            if (card) {
                let oldBtn = card.querySelector('.btn-edit-ternak');
                if (oldBtn) {
                    oldKandangId = oldBtn.getAttribute('data-kandang');
                }
            }

            // Mengubah teks tombol menjadi loading state yang rapi
            btnUpdate.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            btnUpdate.disabled = true;
            document.querySelectorAll('#formEditTernak .invalid-feedback').forEach(el => el.innerText = '');

            const formData = new FormData(formEditTernak);
            formData.append('_method', 'PUT'); // Menyesuaikan agar dibaca sebagai PUT oleh Laravel

            fetch(`/ternak/${id}`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Memaksa Laravel membalas pesan JSON, dilarang kirim HTML!
                },
                body: formData
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                
                if (!response.ok) {
                    // Jika error validasi (Misal: Kode Eartag Sudah Digunakan)
                    if (response.status === 422 && isJson) {
                        const errData = await response.json();
                        return Promise.reject({ type: 'validation', errors: errData.errors });
                    }
                    
                    // Jika error server umum (Misal: Query SQL salah / Server AWS Down)
                    let errorMsg = 'Terjadi kesalahan internal pada server.';
                    if (isJson) {
                        const errData = await response.json();
                        errorMsg = errData.message || errorMsg;
                    }
                    return Promise.reject({ type: 'server', message: errorMsg });
                }
                
                if (!isJson) {
                    return Promise.reject({ type: 'server', message: 'Sesi anda mungkin telah berakhir atau ukuran file terlalu besar. Silahkan muat ulang halaman.' });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    let card = document.getElementById(`card-ternak-${id}`);
                    
                    if (card) {
                        let fotoUrl = data.data.dir_foto_hewan ? `/storage/${data.data.dir_foto_hewan}` : '/image/icons/placeholder.png';
                        
                        // Menjalankan render ulang menggunakan template global window
                        card.innerHTML = window.renderTernakCardHTML(data.data, fotoUrl);
                        window.initTooltips(card);
                    }

                    // Dynamic capacity updates
                    let newKandangId = data.data.kandang_id;
                    if (oldKandangId && oldKandangId != newKandangId) {
                        window.decrementKandangCount(oldKandangId);
                    }
                    if (newKandangId && data.data.kandang) {
                        window.updateKandangCapacity(
                            newKandangId,
                            data.data.kandang.ternaks_count,
                            data.data.kandang.kapasitas_maksimal
                        );
                    }

                    // Menutup modal dengan aman
                    let modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditTernak'));
                    if (modalInstance) modalInstance.hide();
                    
                    formEditTernak.reset();
                    if (previewContainerEdit) previewContainerEdit.classList.add('d-none');
                    if (previewImgEdit) previewImgEdit.src = '';
                    $('#edit_ras_id').val('').trigger('change');
                    $('#edit_kandang_id').val('').trigger('change');
                }
            })
            .catch(error => {
                if (error.type === 'validation') {
                    // Mengisi pesan error validasi langsung ke input komponen bersangkutan
                    for (const [key, messages] of Object.entries(error.errors || {})) {
                        let inputEl = document.querySelector(`#formEditTernak [name="${key}"]`);
                        let errorEl = document.getElementById(`error_edit_${key}`);
                        
                        if (inputEl) inputEl.classList.add('is-invalid');
                        if (errorEl) errorEl.innerText = messages[0]; // Pesan "Nomor eartag ini sudah digunakan" tampil di sini
                    }
                } else {
                    // Menampilkan pesan error server global di kotak merah bagian atas modal
                    globalErrorMsg.innerText = error.message;
                    globalErrorAlert.classList.remove('d-none');
                }
            })
            .finally(() => {
                btnUpdate.innerHTML = 'Simpan Perubahan';
                btnUpdate.disabled = false;
            });
        });
    }
});
</script>
@endpush