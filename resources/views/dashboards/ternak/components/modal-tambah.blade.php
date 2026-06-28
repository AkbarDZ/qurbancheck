<div class="modal fade" id="modalTambahTernak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="formTambahTernak" action="{{ route('ternak.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light border-bottom-0">
                    <h1 class="modal-title fs-5 fw-bold text-dark">
                        <i class="bi bi-plus-circle-fill me-2 text-primary"></i> Tambah Data Ternak
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="tambah_global_error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="tambah_global_error_msg"></span>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">No. Eartag</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('nomor_eartag') is-invalid @enderror" name="nomor_eartag" id="inputEartag" value="{{ old('nomor_eartag') }}" required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnGenerateEartag"
                                            title="Generate otomatis jika hewan belum punya tag">
                                            <i class="bi bi-magic"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nomor_eartag" style="font-size: 0.75rem;">
                                        @error('nomor_eartag') {{ $message }} @enderror
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">
                                        Ketik manual atau generate otomatis. <span class="text-danger fw-semibold"><i class="bi bi-exclamation-circle-fill"></i> Nomor eartag harus unik (tidak boleh sama).</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Ras Ternak</label>
                                    <select class="form-select select2-ras @error('ras_id') is-invalid @enderror" name="ras_id" required>
                                        <option value="">-- Pilih Ras --</option>
                                        @foreach($rasTernaks as $ras)
                                        <option value="{{ $ras->id }}" {{ old('ras_id') == $ras->id ? 'selected' : '' }}>{{ $ras->tipeTernak->nama_jenis }} - {{ $ras->nama_ras }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_ras_id" style="font-size: 0.75rem;">
                                        @error('ras_id') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                        <option value="jantan" {{ old('jenis_kelamin') === 'jantan' ? 'selected' : '' }}>Jantan</option>
                                        <option value="betina" {{ old('jenis_kelamin') === 'betina' ? 'selected' : '' }}>Betina</option>
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_jenis_kelamin" style="font-size: 0.75rem;">
                                        @error('jenis_kelamin') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Kandang</label>
                                    <select class="form-select select2-kandang @error('kandang_id') is-invalid @enderror" name="kandang_id" required>
                                        <option value="">-- Pilih Kandang --</option>
                                        @foreach($kandangs as $kandang)
                                        <option value="{{ $kandang->id }}" 
                                            data-count="{{ $kandang->ternaks_count }}" 
                                            data-max="{{ $kandang->kapasitas_maksimal }}" 
                                            data-nama="{{ $kandang->nama_kandang }}"
                                            {{ old('kandang_id') == $kandang->id ? 'selected' : '' }}
                                            {{ $kandang->ternaks_count >= $kandang->kapasitas_maksimal ? 'disabled' : '' }}>
                                            {{ $kandang->nama_kandang }} ({{ $kandang->ternaks_count }}/{{ $kandang->kapasitas_maksimal }})
                                            @if($kandang->ternaks_count >= $kandang->kapasitas_maksimal) - [Penuh] @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_kandang_id" style="font-size: 0.75rem;">
                                        @error('kandang_id') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Berat Awal (Kg)</label>
                                    <input type="number" min="1" step="0.01" class="form-control @error('berat_awal') is-invalid @enderror" name="berat_awal" value="{{ old('berat_awal') }}" required>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_berat_awal" style="font-size: 0.75rem;">
                                        @error('berat_awal') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Asal Usul Hewan</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="tambah_asal_beli" value="beli" {{ old('asal_hewan', 'beli') === 'beli' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="tambah_asal_beli">Beli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asal_hewan" id="tambah_asal_lahir" value="lahir" {{ old('asal_hewan') === 'lahir' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="tambah_asal_lahir">Lahir di Peternakan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="tambah_container_harga_beli">
                                    <label class="form-label small fw-bold text-muted">Harga Beli Awal (Rp)</label>
                                    <input type="number" min="0" step="0.01" class="form-control @error('harga_beli_awal') is-invalid @enderror" name="harga_beli_awal" id="tambah_harga_beli_awal" value="{{ old('harga_beli_awal') }}" placeholder="Contoh: 15000000">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_harga_beli_awal" style="font-size: 0.75rem;">
                                        @error('harga_beli_awal') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="tambah_container_umur_bulan_beli">
                                    <label class="form-label small fw-bold text-muted">Usia Saat Beli (Bulan)</label>
                                    <input type="number" min="1" class="form-control @error('umur_bulan_beli') is-invalid @enderror" name="umur_bulan_beli" id="tambah_umur_bulan_beli" value="{{ old('umur_bulan_beli') }}" placeholder="Contoh: 18">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_umur_bulan_beli" style="font-size: 0.75rem;">
                                        @error('umur_bulan_beli') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 d-none" id="tambah_container_tanggal_lahir">
                                    <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" id="tambah_tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tanggal_lahir" style="font-size: 0.75rem;">
                                        @error('tanggal_lahir') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Foto Ternak (Opsional)</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" id="tambah_foto" accept="image/*">
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_foto" style="font-size: 0.75rem;">
                                        @error('foto') {{ $message }} @enderror
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanTernak">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('btnGenerateEartag').addEventListener('click', function () {
        // Membuat format seperti: QRBN-2606-1234
        let date = new Date();
        let year = date.getFullYear().toString().slice(-2); // Ambil 2 digit tahun (26)
        let month = ('0' + (date.getMonth() + 1)).slice(-2); // Ambil 2 digit bulan (06)
        let randomNum = Math.floor(1000 + Math.random() * 9000); // 4 digit random

        let generatedEartag = `QRBN-${year}${month}-${randomNum}`;

        // Masukkan ke dalam input text
        document.getElementById('inputEartag').value = generatedEartag;
    });


    document.addEventListener("DOMContentLoaded", function () {
    const formTernak = document.getElementById('formTambahTernak');
    const btnSimpan = document.getElementById('btnSimpanTernak');

    // Toggle Asal Usul Hewan (Mutually Exclusive)
    const radioAsalBeli = document.getElementById('tambah_asal_beli');
    const radioAsalLahir = document.getElementById('tambah_asal_lahir');
    const containerHargaBeli = document.getElementById('tambah_container_harga_beli');
    const containerTanggalLahir = document.getElementById('tambah_container_tanggal_lahir');
    const inputHargaBeli = document.getElementById('tambah_harga_beli_awal');
    const inputTanggalLahir = document.getElementById('tambah_tanggal_lahir');
    const inputBeratAwal = document.querySelector('#formTambahTernak [name="berat_awal"]');

    if (inputTanggalLahir) {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth() + 1).padStart(2, '0');
        const d = String(today.getDate()).padStart(2, '0');
        inputTanggalLahir.setAttribute('max', `${y}-${m}-${d}`);
    }

    function toggleAsalUsul() {
        const containerUmurBeli = document.getElementById('tambah_container_umur_bulan_beli');
        const inputUmurBeli = document.getElementById('tambah_umur_bulan_beli');

        if (radioAsalBeli && radioAsalBeli.checked) {
            if (containerHargaBeli) containerHargaBeli.classList.remove('d-none');
            if (inputHargaBeli) {
                inputHargaBeli.disabled = false;
                inputHargaBeli.required = true;
                if (inputHargaBeli.value === '0') {
                    inputHargaBeli.value = '';
                }
            }
            if (containerUmurBeli) containerUmurBeli.classList.remove('d-none');
            if (inputUmurBeli) {
                inputUmurBeli.disabled = false;
                inputUmurBeli.required = true;
            }
            if (containerTanggalLahir) containerTanggalLahir.classList.add('d-none');
            if (inputTanggalLahir) {
                inputTanggalLahir.disabled = true;
                inputTanggalLahir.required = false;
                inputTanggalLahir.value = '';
            }
        } else {
            if (containerHargaBeli) containerHargaBeli.classList.add('d-none');
            if (inputHargaBeli) {
                inputHargaBeli.disabled = true;
                inputHargaBeli.required = false;
                inputHargaBeli.value = '0';
            }
            if (containerUmurBeli) containerUmurBeli.classList.add('d-none');
            if (inputUmurBeli) {
                inputUmurBeli.disabled = true;
                inputUmurBeli.required = false;
                inputUmurBeli.value = '';
            }
            if (containerTanggalLahir) containerTanggalLahir.classList.remove('d-none');
            if (inputTanggalLahir) {
                inputTanggalLahir.disabled = false;
                inputTanggalLahir.required = true;
            }
        }
    }

    if (radioAsalBeli && radioAsalLahir) {
        radioAsalBeli.addEventListener('change', toggleAsalUsul);
        radioAsalLahir.addEventListener('change', toggleAsalUsul);
        toggleAsalUsul();
    }

    if (formTernak) {
        formTernak.addEventListener('reset', function() {
            setTimeout(toggleAsalUsul, 10);
        });
    }
    const containerTernak = document.getElementById('ternakContainer');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // === Preview Foto & Validasi Ukuran (2MB) ===
    const fileInput = document.getElementById('tambah_foto');
    const previewContainer = document.getElementById('tambah_foto_preview_container');
    const previewImg = document.getElementById('tambah_foto_preview');
    const btnRemove = document.getElementById('btnRemoveFotoTambah');
    const errorFoto = document.getElementById('error_foto');

    // Initialize Select2 when modal is shown
    $('#modalTambahTernak').on('shown.bs.modal', function () {
        $('.select2-ras', this).select2({
            dropdownParent: $('#modalTambahTernak'),
            width: '100%'
        });
        $('.select2-kandang', this).select2({
            dropdownParent: $('#modalTambahTernak'),
            width: '100%'
        });
    });

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

    if (btnRemove) {
        btnRemove.addEventListener('click', function () {
            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('d-none');
            if (previewImg) previewImg.src = '';
            if (fileInput) fileInput.classList.remove('is-invalid');
            if (errorFoto) errorFoto.innerText = '';
        });
    }

    // Form submits normally via standard HTTP POST, JS AJAX submission removed.
});

</script>
@endpush
