<div class="modal fade" id="modalEditTernak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="formEditTernak" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                        <input type="text" class="form-control @error('nomor_eartag') is-invalid @enderror" name="nomor_eartag" id="edit_nomor_eartag" value="{{ old('nomor_eartag') }}" required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnGenerateEartagEdit" title="Generate ulang nomor eartag">
                                            <i class="bi bi-magic"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nomor_eartag" style="font-size: 0.75rem;">
                                        @error('nomor_eartag') {{ $message }} @enderror
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">
                                        Ketik manual atau generate otomatis. <span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> Nomor eartag harus unik (tidak boleh sama).</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Ras Ternak</label>
                                    <select class="form-select select2-edit-ras @error('ras_id') is-invalid @enderror" name="ras_id" id="edit_ras_id" required>
                                        @foreach($rasTernaks as $ras)
                                            <option value="{{ $ras->id }}" {{ old('ras_id') == $ras->id ? 'selected' : '' }}>{{ $ras->tipeTernak->nama_jenis }} - {{ $ras->nama_ras }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_ras_id" style="font-size: 0.75rem;">
                                        @error('ras_id') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="edit_jenis_kelamin" required>
                                        <option value="jantan" {{ old('jenis_kelamin') === 'jantan' ? 'selected' : '' }}>Jantan</option>
                                        <option value="betina" {{ old('jenis_kelamin') === 'betina' ? 'selected' : '' }}>Betina</option>
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_jenis_kelamin" style="font-size: 0.75rem;">
                                        @error('jenis_kelamin') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Kandang</label>
                                    <select class="form-select select2-edit-kandang @error('kandang_id') is-invalid @enderror" name="kandang_id" id="edit_kandang_id" required>
                                        @foreach($kandangs as $kandang)
                                            <option value="{{ $kandang->id }}"
                                                data-count="{{ $kandang->ternaks_count }}"
                                                data-max="{{ $kandang->kapasitas_maksimal }}"
                                                data-nama="{{ $kandang->nama_kandang }}"
                                                {{ old('kandang_id') == $kandang->id ? 'selected' : '' }}>
                                                {{ $kandang->nama_kandang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_kandang_id" style="font-size: 0.75rem;">
                                        @error('kandang_id') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Asal Usul Hewan</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="edit_asal_beli" value="beli" {{ old('asal_hewan') === 'beli' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="edit_asal_beli">Beli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="edit_asal_lahir" value="lahir" {{ old('asal_hewan') === 'lahir' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="edit_asal_lahir">Lahir di Peternakan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="edit_container_harga_beli">
                                    <label class="form-label small fw-bold text-muted">Harga Beli Awal (Rp)</label>
                                    <input type="number" min="0" step="0.01" class="form-control @error('harga_beli_awal') is-invalid @enderror" name="harga_beli_awal" id="edit_harga_beli_awal" value="{{ old('harga_beli_awal') }}" placeholder="Contoh: 15000000">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_harga_beli_awal" style="font-size: 0.75rem;">
                                        @error('harga_beli_awal') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="edit_container_umur_bulan_beli">
                                    <label class="form-label small fw-bold text-muted">Usia Saat Ini (Bulan)</label>
                                    <input type="number" min="1" class="form-control @error('umur_bulan_beli') is-invalid @enderror" name="umur_bulan_beli" id="edit_umur_bulan_beli" value="{{ old('umur_bulan_beli') }}" placeholder="Contoh: 18">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_umur_bulan_beli" style="font-size: 0.75rem;">
                                        @error('umur_bulan_beli') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 d-none" id="edit_container_tanggal_lahir">
                                    <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" id="edit_tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_tanggal_lahir" style="font-size: 0.75rem;">
                                        @error('tanggal_lahir') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label class="form-label small fw-bold text-muted">Ganti Foto Ternak <span class="text-secondary fw-normal">(Kosongkan jika tidak diubah)</span></label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" id="edit_foto" accept="image/*">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_foto" style="font-size: 0.75rem;">
                                        @error('foto') {{ $message }} @enderror
                                    </div>
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

    if (editInputTanggalLahir) {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth() + 1).padStart(2, '0');
        const d = String(today.getDate()).padStart(2, '0');
        editInputTanggalLahir.setAttribute('max', `${y}-${m}-${d}`);
    }

    window.editToggleAsalUsul = function() {
        const containerUmurBeli = document.getElementById('edit_container_umur_bulan_beli');
        const inputUmurBeli = document.getElementById('edit_umur_bulan_beli');

        if (editRadioAsalBeli && editRadioAsalBeli.checked) {
            if (editContainerHargaBeli) editContainerHargaBeli.classList.remove('d-none');
            if (editInputHargaBeli) {
                editInputHargaBeli.disabled = false;
                editInputHargaBeli.required = true;
                if (editInputHargaBeli.value === '0') {
                    editInputHargaBeli.value = '';
                }
            }
            if (containerUmurBeli) containerUmurBeli.classList.remove('d-none');
            if (inputUmurBeli) {
                inputUmurBeli.disabled = false;
                inputUmurBeli.required = true;
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
            if (containerUmurBeli) containerUmurBeli.classList.add('d-none');
            if (inputUmurBeli) {
                inputUmurBeli.disabled = true;
                inputUmurBeli.required = false;
                inputUmurBeli.value = '';
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
    // Form submits normally via standard HTTP POST/PUT, JS AJAX submission removed.
});
</script>
@endpush