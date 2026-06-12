<div class="tab-pane fade p-4" id="kandang-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="mb-0">Data Kandang</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKandang"><i
                class="bi bi-plus-lg"></i> Tambah Kandang</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kandang</th>
                    <th>Kapasitas Maksimal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBodyKandang">
                @forelse($kandangs as $index => $kandang)
                <tr id="row-kandang-{{ $kandang->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td class="col-nama">{{ $kandang->nama_kandang }}</td>
                    <td class="col-kapasitas">{{ $kandang->kapasitas_maksimal }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-kandang" data-id="{{ $kandang->id }}"
                            data-nama="{{ $kandang->nama_kandang }}"
                            data-kapasitas="{{ $kandang->kapasitas_maksimal }}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-kandang" data-id="{{ $kandang->id }}"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Data kosong</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahKandang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahKandang">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Kandang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kandang</label>
                        <input type="text" class="form-control" id="nama_kandang" name="nama_kandang" required>
                        <div class="invalid-feedback" id="error_nama_kandang"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Maksimal</label>
                        <input type="number" class="form-control" id="kapasitas_maksimal" name="kapasitas_maksimal"
                            required>
                        <div class="invalid-feedback" id="error_kapasitas_maksimal"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanKandang">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingKandang" role="status"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modalEditKandang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditKandang">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Kandang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_kandang" name="id">

                    <div class="mb-3">
                        <label class="form-label">Nama Kandang</label>
                        <input type="text" class="form-control" id="edit_nama_kandang" name="nama_kandang" required>
                        <div class="invalid-feedback" id="error_edit_nama_kandang"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Maksimal</label>
                        <input type="number" class="form-control" id="edit_kapasitas_maksimal" name="kapasitas_maksimal"
                            required>
                        <div class="invalid-feedback" id="error_edit_kapasitas_maksimal"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateKandang">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEditKandang"
                            role="status"></span>
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

        // --- Handler Form Kandang ---
        const formKandang = document.getElementById('formTambahKandang');
        const tableBodyKandang = document.getElementById('tableBodyKandang');
        const btnSimpanKandang = document.getElementById('btnSimpanKandang');
        const loadingKandang = document.getElementById('loadingKandang');

        formKandang.addEventListener('submit', function (e) {
            e.preventDefault();
            btnSimpanKandang.disabled = true;
            loadingKandang.classList.remove('d-none');
            document.querySelectorAll('#formTambahKandang .is-invalid').forEach(el => el.classList
                .remove('is-invalid'));

            fetch('/master/kandang', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: new FormData(formKandang)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let emptyRow = tableBodyKandang.querySelector('td[colspan]');
                        if (emptyRow && emptyRow.innerText.toLowerCase().includes('kosong')) {
                            emptyRow.parentElement.remove();
                        }
                        let tr = document.createElement('tr');
                        tr.id = `row-kandang-${data.data.id}`;
                        tr.innerHTML = `
                        <td>Baru</td>
                        <td class="col-nama">${data.data.nama_kandang}</td>
                        <td class="col-kapasitas">${data.data.kapasitas_maksimal}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-kandang" data-id="${data.data.id}" data-nama="${data.data.nama_kandang}" data-kapasitas="${data.data.kapasitas_maksimal}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-kandang" data-id="${data.data.id}"><i class="bi bi-trash"></i></button>
                        </td>
                    `;
                        tableBodyKandang.insertAdjacentElement('afterbegin', tr);
                        bootstrap.Modal.getInstance(document.getElementById('modalTambahKandang'))
                            .hide();
                        formKandang.reset();
                        alert(data.message);
                    } else if (data.message) {
                        for (const [key, messages] of Object.entries(data.errors || {})) {
                            let inputEl = document.getElementById(key);
                            let errorEl = document.getElementById(`error_${key}`);
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
                    btnSimpanKandang.disabled = false;
                    loadingKandang.classList.add('d-none');
                });
        });

        // edit
        const formEditKandang = document.getElementById('formEditKandang');
        const btnUpdateKandang = document.getElementById('btnUpdateKandang');
        const loadingEditKandang = document.getElementById('loadingEditKandang');
        const modalEditKandangInstance = new bootstrap.Modal(document.getElementById('modalEditKandang'));

        tableBodyKandang.addEventListener('click', function (e) {
            let btnEdit = e.target.closest('.btn-edit-kandang');

            if (btnEdit) {
                let id = btnEdit.getAttribute('data-id');
                let nama = btnEdit.getAttribute('data-nama');
                let kapasitas = btnEdit.getAttribute('data-kapasitas');

                document.getElementById('edit_id_kandang').value = id;
                document.getElementById('edit_nama_kandang').value = nama;
                document.getElementById('edit_kapasitas_maksimal').value = kapasitas;

                modalEditKandangInstance.show();
            }
        });

        formEditKandang.addEventListener('submit', function (e) {
            e.preventDefault();

            let id = document.getElementById('edit_id_kandang').value;

            btnUpdateKandang.disabled = true;
            loadingEditKandang.classList.remove('d-none');
            document.querySelectorAll('#formEditKandang .is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));

            const formData = new FormData(formEditKandang);
            formData.append('_method', 'PUT');

            fetch(`/master/kandang/${id}`, {
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
                        let tr = document.getElementById(`row-kandang-${id}`);
                        tr.querySelector('.col-nama').innerText = data.data.nama_kandang;
                        tr.querySelector('.col-kapasitas').innerText = data.data.kapasitas_maksimal;

                        let btnEdit = tr.querySelector('.btn-edit-kandang');
                        btnEdit.setAttribute('data-nama', data.data.nama_kandang);
                        btnEdit.setAttribute('data-kapasitas', data.data.kapasitas_maksimal);

                        modalEditKandangInstance.hide();
                        alert(data.message);
                    } else if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors || {})) {
                            let inputEl = document.getElementById(`edit_${key}`);
                            let errorEl = document.getElementById(`error_edit_${key}`);
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
                    btnUpdateKandang.disabled = false;
                    loadingEditKandang.classList.add('d-none');
                });
        });
        // ====== FITUR HAPUS KANDANG ====== //
        tableBodyKandang.addEventListener('click', function (e) {
            let btnDelete = e.target.closest('.btn-delete-kandang');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                if (confirm('Apakah Anda yakin ingin menghapus kandang ini?')) {

                    let originalIcon = btnDelete.innerHTML;
                    btnDelete.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    btnDelete.disabled = true;

                    fetch(`/master/kandang/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let tr = document.getElementById(`row-kandang-${id}`);
                                if (tr) {
                                    tr.remove();
                                }

                                if (tableBodyKandang.querySelectorAll('tr').length === 0) {
                                    tableBodyKandang.innerHTML =
                                        '<tr><td colspan="4" class="text-center text-muted">Data kosong</td></tr>';
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
