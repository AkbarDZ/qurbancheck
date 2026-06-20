<div class="modal fade" id="modalTambahKesehatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" id="formTambahKesehatan" method="POST" enctype="multipart/form-data">
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
                                <select class="form-select select2-ternak" name="ternak_id" required>
                                    <option value="">-- Pilih Eartag Hewan --</option>
                                    @foreach($ternaks as $ternak)
                                        <option value="{{ $ternak->id }}">{{ $ternak->nomor_eartag }} {{ $ternak->nama_panggilan ? '('.$ternak->nama_panggilan.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Tanggal Rekam <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_rekam" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Gejala Klinis <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="gejala" rows="2" placeholder="Deskripsikan gejala..." required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Foto Gejala <span class="fw-normal text-secondary">(Opsional)</span></label>
                                <input type="file" class="form-control" name="foto_gejala" accept="image/*">
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
    // 2. LOGIKA AJAX SUBMIT FORM
    // =======================================================================
    if (formKesehatan) {
        formKesehatan.addEventListener('submit', function (e) {
            e.preventDefault();
            
            let originalText = btnSimpan.innerHTML;
            btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
            btnSimpan.disabled = true;

            globalErrorAlert.classList.add('d-none');
            document.querySelectorAll('#formTambahKesehatan .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#formTambahKesehatan .invalid-feedback').forEach(el => el.innerText = '');

            const formData = new FormData(formKesehatan);

            fetch('/kesehatan', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                if (!response.ok) {
                    if (response.status === 422 && isJson) {
                        const errData = await response.json();
                        return Promise.reject({ type: 'validation', errors: errData.errors });
                    }
                    let errorMsg = 'Terjadi kesalahan internal pada server.';
                    if (isJson) {
                        const errData = await response.json();
                        errorMsg = errData.message || errorMsg;
                    }
                    return Promise.reject({ type: 'server', message: errorMsg });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    let emptyState = document.getElementById('emptyStateKesehatan');
                    if (emptyState) emptyState.remove();

                    let newRow = document.createElement('tr');
                    newRow.id = `row-kesehatan-${data.data.id}`;
                    newRow.innerHTML = window.renderKesehatanRowHTML(data.data);

                    containerKesehatan.insertAdjacentElement('afterbegin', newRow);
                    if (typeof window.updateCounterKesehatan === 'function') window.updateCounterKesehatan(1);

                    let modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKesehatan'));
                    if (modalInstance) modalInstance.hide();
                    
                    formKesehatan.reset();
                    $('#modalTambahKesehatan .select2-ternak').val('').trigger('change');

                    let container = document.getElementById('container-pengobatan');
                    let items = container.querySelectorAll('.pengobatan-item');
                    for(let i = 1; i < items.length; i++) { 
                        items[i].remove(); 
                    }

                    alert(data.message);
                }
            })
            .catch(error => {
                if (error.type === 'validation') {
                    for (const [key, messages] of Object.entries(error.errors || {})) {
                        let indexMatch = key.match(/\.(\d+)$/); 
                        
                        if(indexMatch) {
                            let cleanName = key.split('.')[0] + '[]'; 
                            let index = parseInt(indexMatch[1]);
                            let inputs = document.querySelectorAll(`#formTambahKesehatan [name="${cleanName}"]`);
                            
                            if(inputs[index]) {
                                inputs[index].classList.add('is-invalid');
                                let container = inputs[index].closest('div');
                                let errorEl = container.querySelector('.invalid-feedback') || container.nextElementSibling;
                                if(errorEl) errorEl.innerText = messages[0];
                            }
                        } else {
                            let inputEl = document.querySelector(`#formTambahKesehatan [name="${key}"]`);
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                                let container = inputEl.closest('div');
                                let errorEl = container.querySelector('.invalid-feedback') || container.nextElementSibling;
                                if (errorEl) errorEl.innerText = messages[0];
                            }
                        }
                    }
                } else {
                    globalErrorMsg.innerText = error.message;
                    globalErrorAlert.classList.remove('d-none');
                }
            })
            .finally(() => {
                btnSimpan.innerHTML = originalText;
                btnSimpan.disabled = false;
            });
        });
    }
});
</script>
@endpush