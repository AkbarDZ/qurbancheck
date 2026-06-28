<div class="modal fade" id="modalTambahKesehatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" id="formTambahKesehatan" action="{{ url('/kesehatan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-light border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-dark">Catat Pemeriksaan & Pengobatan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                
                <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="tambah_global_error_kesehatan">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="tambah_global_error_msg_kesehatan"></span>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Data Pemeriksaan Awal</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Pilih Hewan (Eartag) <span class="text-danger">*</span></label>
                                <select class="form-select select2-ternak @error('ternak_id') is-invalid @enderror" name="ternak_id" required>
                                    <option value="">-- Pilih Eartag Hewan --</option>
                                    @foreach($ternaks as $ternak)
                                        <option value="{{ $ternak->id }}" {{ old('ternak_id') == $ternak->id ? 'selected' : '' }}>{{ $ternak->nomor_eartag }} {{ $ternak->nama_panggilan ? '('.$ternak->nama_panggilan.')' : '' }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_ternak_id" style="font-size: 0.75rem;">
                                    @error('ternak_id') {{ $message }} @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Tanggal Rekam <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_rekam') is-invalid @enderror" name="tanggal_rekam" id="tambah_tanggal_rekam" value="{{ old('tanggal_rekam', date('Y-m-d')) }}" required>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tanggal_rekam" style="font-size: 0.75rem;">
                                    @error('tanggal_rekam') {{ $message }} @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Gejala Klinis <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('gejala') is-invalid @enderror" name="gejala" rows="2" placeholder="Deskripsikan gejala..." required>{{ old('gejala') }}</textarea>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_gejala" style="font-size: 0.75rem;">
                                    @error('gejala') {{ $message }} @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Foto Gejala <span class="fw-normal text-secondary">(Opsional)</span></label>
                                <input type="file" class="form-control @error('foto_gejala') is-invalid @enderror" name="foto_gejala" id="tambah_foto_gejala" accept="image/*">
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_foto_gejala" style="font-size: 0.75rem;">
                                    @error('foto_gejala') {{ $message }} @enderror
                                </div>
                                <div class="form-text text-muted" style="font-size: 0.7rem;">
                                    Format gambar (JPG, PNG, WEBP). <span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> Ukuran foto maksimal 2MB.</span>
                                </div>
                                
                                <div class="mt-3 d-none" id="tambah_foto_preview_container">
                                    <label class="form-label small text-muted fw-bold d-block">Preview Foto:</label>
                                    <div class="position-relative d-inline-block rounded border overflow-hidden bg-light shadow-sm" style="width: 150px; height: 112px; border: 2px dashed #dee2e6 !important;">
                                        <img src="" id="tambah_foto_preview" class="w-100 h-100 object-fit-cover">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 py-0 px-1" id="btnRemoveFotoTambah" style="font-size: 0.75rem; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="form-check form-switch bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded p-3">
                                    <input class="form-check-input ms-0 me-3 mt-1" type="checkbox" name="status_karantina" value="1" id="switchKarantina" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                    <label class="form-check-label fw-bold text-danger mt-1" for="switchKarantina" style="cursor: pointer;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Pindahkan Hewan ke Karantina
                                    </label>
                                    <div class="form-text ms-5 mt-0 text-dark opacity-75">Centang jika hewan perlu dipisahkan dari kawanannya untuk mencegah penularan.</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-success mb-0">Rincian Tindakan & Pengobatan</h6>
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill" id="btnAddPengobatan">
                        <i class="bi bi-plus-lg"></i> Tambah Tindakan Lain
                    </button>
                </div>

                <div id="container-pengobatan">
                    <div class="card border border-success border-opacity-25 shadow-sm mb-3 pengobatan-item position-relative">
                        
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-pengobatan d-none" title="Hapus Tindakan" style="z-index: 10;">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="card-body bg-white p-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Tindakan / Nama Obat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-obat" name="nama_obat_tindakan[]" placeholder="Misal: Cabut Gigi / Obat Antibiotik" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Dosis <span class="text-secondary">(Opsional)</span></label>
                                    <input type="text" class="form-control input-dosis" name="dosis[]" placeholder="Misal: 10 ml">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Biaya Pengobatan <span class="text-secondary">(Opsional)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rp</span>
                                        <input type="number" class="form-control input-biaya" name="biaya_pengobatan[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold text-muted">Catatan <span class="text-secondary">(Opsional)</span></label>
                                    <input type="text" class="form-control input-catatan" name="catatan[]" placeholder="Catatan khusus obat ini...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-white border-top py-2">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-4" id="btnSimpanKesehatan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const formKesehatan = document.getElementById('formTambahKesehatan');
    const btnSimpan = document.getElementById('btnSimpanKesehatan');
    const containerKesehatan = document.getElementById('kesehatanContainer');
    
    const globalErrorAlert = document.getElementById('tambah_global_error_kesehatan');
    const globalErrorMsg = document.getElementById('tambah_global_error_msg_kesehatan');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const inputTanggalRekam = document.getElementById('tambah_tanggal_rekam');
    if (inputTanggalRekam) {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth() + 1).padStart(2, '0');
        const d = String(today.getDate()).padStart(2, '0');
        inputTanggalRekam.setAttribute('max', `${y}-${m}-${d}`);
    }

    // Initialize Select2 when modal is shown
    $('#modalTambahKesehatan').on('shown.bs.modal', function () {
        $('#modalTambahKesehatan .select2-ternak').select2({
            dropdownParent: $('#modalTambahKesehatan'),
            width: '100%'
        });
    });

    // =======================================================================
    // 1. LOGIKA MULTI INPUT PENGOBATAN 
    // =======================================================================
    document.addEventListener('click', function(e) {
        
        let btnAdd = e.target.closest('#btnAddPengobatan');
        if (btnAdd) {
            e.preventDefault(); 
            
            let container = document.getElementById('container-pengobatan');
            if (!container) return;

            let firstItem = container.querySelector('.pengobatan-item');
            if (!firstItem) return;

            let newItem = firstItem.cloneNode(true);

            newItem.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.classList.remove('is-invalid');
            });

            // (Kode pembersihan <select> karantina dihapus karena sudah tidak ada)

            newItem.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');

            let btnRemove = newItem.querySelector('.btn-remove-pengobatan');
            if (btnRemove) {
                btnRemove.classList.remove('d-none');
            }

            container.appendChild(newItem);
        }

        let btnRemove = e.target.closest('.btn-remove-pengobatan');
        if (btnRemove) {
            e.preventDefault();
            btnRemove.closest('.pengobatan-item').remove();
        }
    });

    // =======================================================================
    // 2. Form submits normally via standard HTTP POST, JS AJAX submission removed.
    // =======================================================================

    // === Preview Foto & Validasi Ukuran (2MB) ===
    const fileInput = document.getElementById('tambah_foto_gejala');
    const previewContainer = document.getElementById('tambah_foto_preview_container');
    const previewImg = document.getElementById('tambah_foto_preview');
    const btnRemoveFoto = document.getElementById('btnRemoveFotoTambah');
    const errorFoto = document.getElementById('error_foto_gejala');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            fileInput.classList.remove('is-invalid');
            if (errorFoto) errorFoto.innerText = '';
            
            if (file) {
                // Check size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    fileInput.classList.add('is-invalid');
                    if (errorFoto) errorFoto.innerText = 'Ukuran foto melebihi 2MB. Silakan pilih file yang lebih kecil.';
                    fileInput.value = ''; // Reset input
                    if (previewContainer) previewContainer.classList.add('d-none');
                    if (previewImg) previewImg.src = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                if (previewContainer) previewContainer.classList.add('d-none');
                if (previewImg) previewImg.src = '';
            }
        });
    }

    if (btnRemoveFoto) {
        btnRemoveFoto.addEventListener('click', function () {
            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('d-none');
            if (previewImg) previewImg.src = '';
            if (fileInput) fileInput.classList.remove('is-invalid');
            if (errorFoto) errorFoto.innerText = '';
        });
    }
});
</script>
@endpush