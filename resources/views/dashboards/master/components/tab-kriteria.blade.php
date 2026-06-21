<div class="tab-pane fade p-4" id="kriteria-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-shield-check me-2 text-primary"></i>Kriteria Syariat</h5>
            <p class="text-muted small mb-0">Kelola daftar kriteria kelayakan hewan qurban berdasarkan hukum syariat Islam.</p>
        </div>
        <button class="btn btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kriteria
        </button>
    </div>
    
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr class="border-bottom border-light-subtle">
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-clipboard-check me-2 text-muted"></i>Kriteria</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 120px;"><i class="bi bi-exclamation-triangle me-2 text-muted"></i>Fatal?</th>
                    <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBodyKriteria">
                @forelse($kriteriaKurbans as $kriteria)
                <tr id="row-kriteria-{{ $kriteria->id }}">
                    <td class="py-3 px-3 col-nama fw-bold text-dark">
                        <div>{{ $kriteria->nama_kriteria }}</div>
                        @if($kriteria->deskripsi)
                            <div class="text-muted small fw-normal mt-1">{{ $kriteria->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-3 col-fatal">
                        @if($kriteria->is_fatal)
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Ya</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Tidak</span>
                        @endif
                    </td>
                    <td class="py-3 px-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-kriteria" data-id="{{ $kriteria->id }}" data-nama="{{ $kriteria->nama_kriteria }}" data-deskripsi="{{ $kriteria->deskripsi }}" data-fatal="{{ $kriteria->is_fatal }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Kriteria"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-kriteria" data-id="{{ $kriteria->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Kriteria"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Kriteria Syariat</h6>
                        <p class="small text-muted mb-0">Klik tombol "Tambah Kriteria" untuk mendaftarkan kriteria syariat baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Container -->
    <div id="paginationKriteria" class="d-flex justify-content-center mt-3"></div>
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
        window.kriteriaPagination = window.initTablePagination('tableBodyKriteria', 'paginationKriteria', 5);

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
                        if (emptyRow && (emptyRow.innerText.toLowerCase().includes('kosong') || emptyRow.innerText.toLowerCase().includes('belum ada'))) {
                            emptyRow.parentElement.remove();
                        }
                        let badge = data.data.is_fatal ? '<span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Ya</span>' :
                            '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Tidak</span>';
                        let descHtml = data.data.deskripsi ? `<div class="text-muted small fw-normal mt-1">${data.data.deskripsi}</div>` : '';
                        let tr = document.createElement('tr');
                        tr.id = `row-kriteria-${data.data.id}`;
                        tr.innerHTML = `
                        <td class="py-3 px-3 col-nama fw-bold text-dark">
                            <div>${data.data.nama_kriteria}</div>
                            ${descHtml}
                        </td>
                        <td class="py-3 px-3 col-fatal">${badge}</td>
                        <td class="py-3 px-3 text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-kriteria" data-id="${data.data.id}" data-nama="${data.data.nama_kriteria}" data-deskripsi="${data.data.deskripsi || ''}" data-fatal="${data.data.is_fatal ? 1 : 0}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Kriteria"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-kriteria" data-id="${data.data.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Kriteria"><i class="bi bi-trash"></i></button>
                        </td>
                    `;
                        tableBodyKriteria.insertAdjacentElement('afterbegin', tr);
                        if (window.kriteriaPagination) window.kriteriaPagination.update();
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
                        
                        let descHtml = data.data.deskripsi ? `<div class="text-muted small fw-normal mt-1">${data.data.deskripsi}</div>` : '';
                        tr.querySelector('.col-nama').innerHTML = `<div>${data.data.nama_kriteria}</div>${descHtml}`;
                        
                        let badge = data.data.is_fatal ? '<span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Ya</span>' :
                            '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Tidak</span>';
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

                Swal.fire({
                    title: 'Hapus Kriteria?',
                    text: "Apakah Anda yakin ingin menghapus kriteria ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                    if (window.kriteriaPagination) window.kriteriaPagination.update();

                                    if (tableBodyKriteria.querySelectorAll('tr').length === 0) {
                                        tableBodyKriteria.innerHTML =
                                            '<tr><td colspan="3" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i><h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Kriteria Syariat</h6><p class="small text-muted mb-0">Klik tombol "Tambah Kriteria" untuk mendaftarkan kriteria syariat baru.</p></td></tr>';
                                    }

                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#428475'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Gagal menghapus data.',
                                        icon: 'error',
                                        confirmButtonColor: '#428475'
                                    });
                                    btnDelete.innerHTML = originalIcon;
                                    btnDelete.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menghubungi server.',
                                    icon: 'error',
                                    confirmButtonColor: '#428475'
                                });
                                btnDelete.innerHTML = originalIcon;
                                btnDelete.disabled = false;
                            });
                    }
                });
            }
        });
    });

</script>
@endpush
