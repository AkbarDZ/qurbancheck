<div class="tab-pane fade p-4" id="kriteria-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="mb-0">Kriteria Syariat</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
            data-bs-target="#modalTambahKriteria"><i class="bi bi-plus-lg"></i> Tambah Kriteria</button>
    </div>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Kriteria</th>
                <th>Fatal?</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBodyKriteria">
            @forelse($kriteriaKurbans as $kriteria)
            <tr id="row-kriteria-{{ $kriteria->id }}">
                <td class="col-nama">{{ $kriteria->nama_kriteria }}</td>
                <td class="col-fatal">
                    @if($kriteria->is_fatal)
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-secondary">Tidak</span>
                    @endif
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary btn-edit-kriteria" data-id="{{ $kriteria->id }}" data-nama="{{ $kriteria->nama_kriteria }}" data-deskripsi="{{ $kriteria->deskripsi }}" data-fatal="{{ $kriteria->is_fatal }}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger btn-delete-kriteria" data-id="{{ $kriteria->id }}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Data kosong</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahKriteria">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Kriteria Syariat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" required>
                        <div class="invalid-feedback" id="error_nama_kriteria"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi_kriteria" name="deskripsi" rows="2"></textarea>
                        <div class="invalid-feedback" id="error_deskripsi_kriteria"></div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_fatal" name="is_fatal" value="1">
                        <label class="form-check-label" for="is_fatal">Kriteria Fatal (Tidak sah qurban)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanKriteria">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingKriteria" role="status"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modalEditKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditKriteria">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Kriteria Syariat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_kriteria" name="id">

                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="edit_nama_kriteria" name="nama_kriteria" required>
                        <div class="invalid-feedback" id="error_edit_nama_kriteria"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi_kriteria" name="deskripsi" rows="2"></textarea>
                        <div class="invalid-feedback" id="error_edit_deskripsi_kriteria"></div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_fatal" name="is_fatal" value="1">
                        <label class="form-check-label" for="edit_is_fatal">Kriteria Fatal (Tidak sah qurban)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateKriteria">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEditKriteria" role="status"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // --- Handler Form Kriteria ---
        const formKriteria = document.getElementById('formTambahKriteria');
        const tableBodyKriteria = document.getElementById('tableBodyKriteria');
        const btnSimpanKriteria = document.getElementById('btnSimpanKriteria');
        const loadingKriteria = document.getElementById('loadingKriteria');

        formKriteria.addEventListener('submit', function (e) {
            e.preventDefault();
            btnSimpanKriteria.disabled = true;
            loadingKriteria.classList.remove('d-none');
            document.querySelectorAll('#formTambahKriteria .is-invalid').forEach(el => el.classList
                .remove('is-invalid'));

            fetch('/master/kriteria', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: new FormData(formKriteria)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let emptyRow = tableBodyKriteria.querySelector('td[colspan]');
                        if (emptyRow && emptyRow.innerText.toLowerCase().includes('kosong')) {
                            emptyRow.parentElement.remove();
                        }
                        let badge = data.data.is_fatal ? '<span class="badge bg-danger">Ya</span>' :
                            '<span class="badge bg-secondary">Tidak</span>';
                        let tr = document.createElement('tr');
                        tr.id = `row-kriteria-${data.data.id}`;
                        tr.innerHTML = `
                        <td class="col-nama">${data.data.nama_kriteria}</td>
                        <td class="col-fatal">${badge}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-kriteria" data-id="${data.data.id}" data-nama="${data.data.nama_kriteria}" data-deskripsi="${data.data.deskripsi || ''}" data-fatal="${data.data.is_fatal ? 1 : 0}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-kriteria" data-id="${data.data.id}"><i class="bi bi-trash"></i></button>
                        </td>
                    `;
                        tableBodyKriteria.insertAdjacentElement('afterbegin', tr);
                        bootstrap.Modal.getInstance(document.getElementById('modalTambahKriteria'))
                            .hide();
                        formKriteria.reset();
                        alert(data.message);
                    } else if (data.message) {
                        for (const [key, messages] of Object.entries(data.errors || {})) {
                            // Special handling for deskripsi error id vs name
                            let errKey = key === 'deskripsi' ? 'deskripsi_kriteria' : key;
                            let inputEl = document.getElementsByName(key)[0];
                            let errorEl = document.getElementById(`error_${errKey}`);
                            if (inputEl && errorEl) {
                                inputEl.classList.add('is-invalid');
                                errorEl.innerText = messages[0];
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan.');
                })
                .finally(() => {
                    btnSimpanKriteria.disabled = false;
                    loadingKriteria.classList.add('d-none');
                });
        });

        // edit
        const formEditKriteria = document.getElementById('formEditKriteria');
        const btnUpdateKriteria = document.getElementById('btnUpdateKriteria');
        const loadingEditKriteria = document.getElementById('loadingEditKriteria');
        const modalEditKriteriaInstance = new bootstrap.Modal(document.getElementById('modalEditKriteria'));

        tableBodyKriteria.addEventListener('click', function (e) {
            let btnEdit = e.target.closest('.btn-edit-kriteria');

            if (btnEdit) {
                let id = btnEdit.getAttribute('data-id');
                let nama = btnEdit.getAttribute('data-nama');
                let deskripsi = btnEdit.getAttribute('data-deskripsi');
                let fatal = btnEdit.getAttribute('data-fatal');

                document.getElementById('edit_id_kriteria').value = id;
                document.getElementById('edit_nama_kriteria').value = nama;
                document.getElementById('edit_deskripsi_kriteria').value = deskripsi;
                document.getElementById('edit_is_fatal').checked = fatal == 1 || fatal == 'true';

                modalEditKriteriaInstance.show();
            }
        });

        formEditKriteria.addEventListener('submit', function (e) {
            e.preventDefault();

            let id = document.getElementById('edit_id_kriteria').value;

            btnUpdateKriteria.disabled = true;
            loadingEditKriteria.classList.remove('d-none');
            document.querySelectorAll('#formEditKriteria .is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));

            const formData = new FormData(formEditKriteria);
            formData.append('_method', 'PUT');

            fetch(`/master/kriteria/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let tr = document.getElementById(`row-kriteria-${id}`);
                        tr.querySelector('.col-nama').innerText = data.data.nama_kriteria;
                        let badge = data.data.is_fatal ? '<span class="badge bg-danger">Ya</span>' :
                            '<span class="badge bg-secondary">Tidak</span>';
                        tr.querySelector('.col-fatal').innerHTML = badge;

                        let btnEdit = tr.querySelector('.btn-edit-kriteria');
                        btnEdit.setAttribute('data-nama', data.data.nama_kriteria);
                        btnEdit.setAttribute('data-deskripsi', data.data.deskripsi || '');
                        btnEdit.setAttribute('data-fatal', data.data.is_fatal ? 1 : 0);

                        modalEditKriteriaInstance.hide();
                        alert(data.message);
                    } else if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors || {})) {
                            let errKey = key === 'deskripsi' ? 'deskripsi_kriteria' : key;
                            let inputEl = document.getElementById(`edit_${errKey}`);
                            let errorEl = document.getElementById(`error_edit_${errKey}`);
                            if (inputEl && errorEl) {
                                inputEl.classList.add('is-invalid');
                                errorEl.innerText = messages[0];
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan.');
                })
                .finally(() => {
                    btnUpdateKriteria.disabled = false;
                    loadingEditKriteria.classList.add('d-none');
                });
        });
        // ====== FITUR HAPUS KRITERIA ====== //
        tableBodyKriteria.addEventListener('click', function (e) {
            let btnDelete = e.target.closest('.btn-delete-kriteria');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                if (confirm('Apakah Anda yakin ingin menghapus kriteria ini?')) {

                    let originalIcon = btnDelete.innerHTML;
                    btnDelete.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    btnDelete.disabled = true;

                    fetch(`/master/kriteria/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let tr = document.getElementById(`row-kriteria-${id}`);
                                if (tr) {
                                    tr.remove();
                                }

                                if (tableBodyKriteria.querySelectorAll('tr').length === 0) {
                                    tableBodyKriteria.innerHTML =
                                        '<tr><td colspan="3" class="text-center text-muted">Data kosong</td></tr>';
                                }

                                alert(data.message);
                            } else {
                                alert('Gagal menghapus data.');
                                btnDelete.innerHTML = originalIcon;
                                btnDelete.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghubungi server.');
                            btnDelete.innerHTML = originalIcon;
                            btnDelete.disabled = false;
                        });
                }
            }
        });
    });

</script>
@endpush
