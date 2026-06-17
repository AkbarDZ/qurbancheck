<div class="modal fade" id="modalEditTernak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="formEditTernak" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold">Edit Data Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="edit_global_error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="edit_global_error_msg"></span>
                    </div>

                    <input type="hidden" id="edit_ternak_id" name="id">
                    
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
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Ras Ternak</label>
                            <select class="form-select" name="ras_id" id="edit_ras_id" required>
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
                            <select class="form-select" name="kandang_id" id="edit_kandang_id" required>
                                @foreach($kandangs as $kandang)
                                    <option value="{{ $kandang->id }}">{{ $kandang->nama_kandang }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_kandang_id" style="font-size: 0.75rem;"></div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label small fw-bold text-muted">Ganti Foto Ternak <span class="text-secondary fw-normal">(Kosongkan jika tidak diubah)</span></label>
                            <input type="file" class="form-control" name="foto" id="edit_foto" accept="image/*">
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_foto" style="font-size: 0.75rem;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-2">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-3" id="btnUpdateTernak">Simpan Perubahan</button>
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
    
    const globalErrorAlert = document.getElementById('edit_global_error');
    const globalErrorMsg = document.getElementById('edit_global_error_msg');
    
    // Pastikan token CSRF tersedia
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

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

            // Mengubah teks tombol menjadi loading state yang rapi
            btnUpdate.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            btnUpdate.disabled = true;
            
            // Reset seluruh visual error lama
            globalErrorAlert.classList.add('d-none');
            document.querySelectorAll('#formEditTernak .is-invalid').forEach(el => el.classList.remove('is-invalid'));
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

                    // Menutup modal dengan aman
                    let modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditTernak'));
                    if (modalInstance) modalInstance.hide();
                    
                    formEditTernak.reset();
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