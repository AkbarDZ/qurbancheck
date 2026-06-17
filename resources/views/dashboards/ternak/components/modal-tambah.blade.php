<div class="modal fade" id="modalTambahTernak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTambahTernak" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Data Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="tambah_global_error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="tambah_global_error_msg"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Eartag</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nomor_eartag" id="inputEartag" required>
                                <button class="btn btn-outline-secondary" type="button" id="btnGenerateEartag"
                                    title="Generate otomatis jika hewan belum punya tag">
                                    <i class="bi bi-magic"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nomor_eartag" style="font-size: 0.75rem;"></div>
                            <div class="form-text" style="font-size: 0.7rem;">Ketik manual, atau gunakan tombol generate otomatis</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ras Ternak</label>
                            <select class="form-select" name="ras_id" required>
                                <option value="">-- Pilih Ras --</option>
                                @foreach($rasTernaks as $ras)
                                <option value="{{ $ras->id }}">{{ $ras->tipeTernak->nama_jenis }} - {{ $ras->nama_ras }}
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_ras_id" style="font-size: 0.75rem;"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="jantan">Jantan</option>
                                <option value="betina">Betina</option>
                            </select>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_jenis_kelamin" style="font-size: 0.75rem;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kandang</label>
                            <select class="form-select" name="kandang_id" required>
                                @foreach($kandangs as $kandang)
                                <option value="{{ $kandang->id }}">{{ $kandang->nama_kandang }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_kandang_id" style="font-size: 0.75rem;"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Berat Awal (Kg)</label>
                            <input type="number" step="0.01" class="form-control" name="berat_awal" required>
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_berat_awal" style="font-size: 0.75rem;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto Ternak (Opsional)</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                            <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_foto" style="font-size: 0.75rem;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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
    const containerTernak = document.getElementById('ternakContainer');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    formTernak.addEventListener('submit', function (e) {
        e.preventDefault();
        btnSimpan.disabled = true;
        document.querySelectorAll('#formTambahTernak .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#formTambahTernak .invalid-feedback').forEach(el => el.innerText = '');
        
        const globalErrorAlert = document.getElementById('tambah_global_error');
        const globalErrorMsg = document.getElementById('tambah_global_error_msg');
        if (globalErrorAlert) globalErrorAlert.classList.add('d-none');
        
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

        const formData = new FormData(formTernak);

        fetch('/ternak', {
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
            if (!isJson) {
                // Prevent "Unexpected token <" if server returns HTML unexpectedly (e.g. session expired, or redirect)
                return Promise.reject({ type: 'server', message: 'Sesi anda mungkin telah berakhir atau ukuran file terlalu besar. Silahkan muat ulang halaman.' });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // Hapus empty state jika ada
                let emptyState = document.getElementById('emptyStateTernak');
                if (emptyState) emptyState.remove();

                let fotoUrl = data.data.dir_foto_hewan ? `/storage/${data.data.dir_foto_hewan}` : '/image/icons/placeholder.png';
                
                // MEMANGGIL TEMPLATE KARTU GLOBAL
                let newCard = document.createElement('div');
                newCard.className = "card shadow-sm border-0 mb-4";
                newCard.id = `card-ternak-${data.data.id}`;
                newCard.innerHTML = window.renderTernakCardHTML(data.data, fotoUrl);

                containerTernak.insertAdjacentElement('afterbegin', newCard);
                
                // Panggil utilitas pembantu global
                window.initTooltips(newCard);
                window.updateCounter(1);

                // TUTUP MODAL SECARA AMAN (Mencegah Bug Layar Gelap)
                let modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahTernak'));
                if (modalInstance) modalInstance.hide();
                
                formTernak.reset();
                alert(data.message);
            }
        })
        .catch(err => {
            if (err.type === 'validation') {
                for (const [key, messages] of Object.entries(err.errors || {})) {
                    let inputEl = document.querySelector(`#formTambahTernak [name="${key}"]`);
                    let errorEl = document.getElementById(`error_${key}`);
                    if (inputEl) inputEl.classList.add('is-invalid');
                    if (errorEl) errorEl.innerText = messages[0];
                }
            } else {
                if (globalErrorMsg) globalErrorMsg.innerText = err.message || 'Terjadi kesalahan pada server.';
                if (globalErrorAlert) globalErrorAlert.classList.remove('d-none');
            }
        })
        .finally(() => {
            btnSimpan.innerHTML = 'Simpan Data';
            btnSimpan.disabled = false;
        });
    });
});

</script>
@endpush
