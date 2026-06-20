<div class="tab-pane fade p-4" id="ras-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-tag-fill me-2 text-primary"></i>Data Ras Ternak</h5>
            <p class="text-muted small mb-0">Kelola spesifikasi ras/keturunan hewan qurban berdasarkan tipenya.</p>
        </div>
        <button class="btn btn btn-success px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRas">
            <i class="bi bi-plus-lg me-1"></i> Tambah Ras
        </button>
    </div>
    
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr class="border-bottom border-light-subtle">
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-tag me-2 text-muted"></i>Jenis Ternak</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-intersect me-2 text-muted"></i>Nama Ras</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-file-text me-2 text-muted"></i>Deskripsi</th>
                    <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBodyRas">
                @forelse($rasTernaks as $index => $ras)
                <tr id="row-ras-{{ $ras->id }}">
                    <td class="py-3 px-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                    <td class="py-3 px-3 col-tipe">
                        <span class="badge bg-info-subtle text-dark border border-info px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                            {{ $ras->tipeTernak->nama_jenis ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-3 col-nama fw-bold text-dark">{{ $ras->nama_ras }}</td>
                    <td class="py-3 px-3 col-deskripsi text-secondary small">{{ $ras->deskripsi ?: '-' }}</td>
                    <td class="py-3 px-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-ras" data-id="{{ $ras->id }}"
                            data-tipe="{{ $ras->tipe_ternak_id }}" data-nama="{{ $ras->nama_ras }}"
                            data-deskripsi="{{ $ras->deskripsi }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Ras"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-ras" data-id="{{ $ras->id }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Ras"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Ras Ternak</h6>
                        <p class="small text-muted mb-0">Klik tombol "Tambah Ras" untuk mendaftarkan kategori ras baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Container -->
    <div id="paginationRas" class="d-flex justify-content-center mt-3"></div>
</div>

<div class="modal fade" id="modalTambahRas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahRas">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Ras Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Ternak</label>
                        <select class="form-select" id="tipe_ternak_id" name="tipe_ternak_id" required>
                            <option value="">Pilih Tipe Ternak...</option>
                            @foreach($tipeTernaks as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_tipe_ternak_id"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Ras</label>
                        <input type="text" class="form-control" id="nama_ras" name="nama_ras" required>
                        <div class="invalid-feedback" id="error_nama_ras"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2"></textarea>
                        <div class="invalid-feedback" id="error_deskripsi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanRas">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingRas" role="status"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modalEditRas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditRas">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Ras Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_ras" name="id">

                    <div class="mb-3">
                        <label class="form-label">Tipe Ternak</label>
                        <select class="form-select" id="edit_tipe_ternak_id" name="tipe_ternak_id" required>
                            <option value="">Pilih Tipe Ternak...</option>
                            @foreach($tipeTernaks as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_edit_tipe_ternak_id"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Ras</label>
                        <input type="text" class="form-control" id="edit_nama_ras" name="nama_ras" required>
                        <div class="invalid-feedback" id="error_edit_nama_ras"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="2"></textarea>
                        <div class="invalid-feedback" id="error_edit_deskripsi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateRas">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEditRas" role="status"></span>
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
        window.rasPagination = window.initTablePagination('tableBodyRas', 'paginationRas', 5);

        // --- Handler Form Ras ---
        const formRas = document.getElementById('formTambahRas');
        const tableBodyRas = document.getElementById('tableBodyRas');
        const btnSimpanRas = document.getElementById('btnSimpanRas');
        const loadingRas = document.getElementById('loadingRas');

        formRas.addEventListener('submit', function (e) {
            e.preventDefault();
            btnSimpanRas.disabled = true;
            loadingRas.classList.remove('d-none');
            document.querySelectorAll('#formTambahRas .is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));

            fetch('/master/ras', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: new FormData(formRas)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let emptyRow = tableBodyRas.querySelector('td[colspan]');
                        if (emptyRow && (emptyRow.innerText.toLowerCase().includes('kosong') || emptyRow.innerText.toLowerCase().includes('belum ada'))) {
                            emptyRow.parentElement.remove();
                        }
                        let tr = document.createElement('tr');
                        tr.id = `row-ras-${data.data.id}`;
                        tr.innerHTML = `
                        <td class="py-3 px-3 fw-semibold text-secondary">Baru</td>
                        <td class="py-3 px-3 col-tipe">
                            <span class="badge bg-info-subtle text-dark border border-info px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">${data.data.tipe_ternak.nama_jenis}</span>
                        </td>
                        <td class="py-3 px-3 col-nama fw-bold text-dark">${data.data.nama_ras}</td>
                        <td class="py-3 px-3 col-deskripsi text-secondary small">${data.data.deskripsi || '-'}</td>
                        <td class="py-3 px-3 text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-ras" data-id="${data.data.id}" data-tipe="${data.data.tipe_ternak_id}" data-nama="${data.data.nama_ras}" data-deskripsi="${data.data.deskripsi || ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Ras"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-ras" data-id="${data.data.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Ras"><i class="bi bi-trash"></i></button>
                        </td>
                    `;
                        tableBodyRas.insertAdjacentElement('afterbegin', tr);
                        if (window.rasPagination) window.rasPagination.update();
                        bootstrap.Modal.getInstance(document.getElementById('modalTambahRas'))
                            .hide();
                        formRas.reset();
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
                    btnSimpanRas.disabled = false;
                    loadingRas.classList.add('d-none');
                });
        });

        // edit
        const formEditRas = document.getElementById('formEditRas');
        const btnUpdateRas = document.getElementById('btnUpdateRas');
        const loadingEditRas = document.getElementById('loadingEditRas');
        const modalEditRasInstance = new bootstrap.Modal(document.getElementById('modalEditRas'));

        tableBodyRas.addEventListener('click', function (e) {
            let btnEdit = e.target.closest('.btn-edit-ras');

            if (btnEdit) {
                let id = btnEdit.getAttribute('data-id');
                let tipe = btnEdit.getAttribute('data-tipe');
                let nama = btnEdit.getAttribute('data-nama');
                let deskripsi = btnEdit.getAttribute('data-deskripsi');

                document.getElementById('edit_id_ras').value = id;
                document.getElementById('edit_tipe_ternak_id').value = tipe;
                document.getElementById('edit_nama_ras').value = nama;
                document.getElementById('edit_deskripsi').value = deskripsi;

                modalEditRasInstance.show();
            }
        });

        formEditRas.addEventListener('submit', function (e) {
            e.preventDefault();

            let id = document.getElementById('edit_id_ras').value;

            btnUpdateRas.disabled = true;
            loadingEditRas.classList.remove('d-none');
            document.querySelectorAll('#formEditRas .is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));

            const formData = new FormData(formEditRas);
            formData.append('_method', 'PUT');

            fetch(`/master/ras/${id}`, {
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
                        let tr = document.getElementById(`row-ras-${id}`);
                        let tipeText = formEditRas.querySelector(
                            `select option[value="${data.data.tipe_ternak_id}"]`).text;
                        tr.querySelector('.col-tipe').innerHTML =
                            `<span class="badge bg-info-subtle text-dark border border-info px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">${tipeText}</span>`;
                        tr.querySelector('.col-nama').innerText = data.data.nama_ras;
                        tr.querySelector('.col-deskripsi').innerText = data.data.deskripsi || '-';

                        let btnEdit = tr.querySelector('.btn-edit-ras');
                        btnEdit.setAttribute('data-tipe', data.data.tipe_ternak_id);
                        btnEdit.setAttribute('data-nama', data.data.nama_ras);
                        btnEdit.setAttribute('data-deskripsi', data.data.deskripsi || '');

                        modalEditRasInstance.hide();
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
                    btnUpdateRas.disabled = false;
                    loadingEditRas.classList.add('d-none');
                });
        });

        // ====== FITUR HAPUS TIPE TERNAK ====== //

        // Kita tetap menggunakan event delegation pada tableBodyRas
        tableBodyRas.addEventListener('click', function (e) {
            // Cari elemen terdekat yang memiliki class .btn-delete-ras
            let btnDelete = e.target.closest('.btn-delete-ras');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                // Munculkan dialog konfirmasi
                if (confirm('Apakah Anda yakin ingin menghapus ras ini?')) {

                    // UX: Ubah tombol jadi status loading agar tidak diklik dua kali
                    let originalIcon = btnDelete.innerHTML;
                    btnDelete.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    btnDelete.disabled = true;

                    // Lakukan request DELETE
                    fetch(`/master/ras/${id}`, {
                            method: 'DELETE', // Method langsung menggunakan DELETE
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // 1. Cari elemen <tr> berdasarkan ID yang kita buat sebelumnya
                                let tr = document.getElementById(`row-ras-${id}`);
                                if (tr) {
                                    // Hapus elemen dari DOM HTML secara langsung
                                    tr.remove();
                                }
                                if (window.rasPagination) window.rasPagination.update();

                                // 2. Cek apakah tabel sekarang kosong, jika ya, tampilkan pesan kosong
                                if (tableBodyRas.querySelectorAll('tr').length === 0) {
                                    tableBodyRas.innerHTML =
                                        '<tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i><h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Ras Ternak</h6><p class="small text-muted mb-0">Klik tombol "Tambah Ras" untuk mendaftarkan kategori ras baru.</p></td></tr>';
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
        // ====== Sinkronisasi Dropdown Tipe Ternak dari tab-tipe ====== //
        document.addEventListener('tipeTernakAdded', function (e) {
            const tipe = e.detail;
            const selectTambah = document.getElementById('tipe_ternak_id');
            const selectEdit = document.getElementById('edit_tipe_ternak_id');

            if (selectTambah) selectTambah.add(new Option(tipe.nama_jenis, tipe.id));
            if (selectEdit) selectEdit.add(new Option(tipe.nama_jenis, tipe.id));
        });

        document.addEventListener('tipeTernakUpdated', function (e) {
            const tipe = e.detail;
            const selectTambah = document.getElementById('tipe_ternak_id');
            const selectEdit = document.getElementById('edit_tipe_ternak_id');

            if (selectTambah) {
                let opt = selectTambah.querySelector(`option[value="${tipe.id}"]`);
                if (opt) opt.text = tipe.nama_jenis;
            }
            if (selectEdit) {
                let opt = selectEdit.querySelector(`option[value="${tipe.id}"]`);
                if (opt) opt.text = tipe.nama_jenis;
            }

            // Update badge text on ras table
            document.querySelectorAll('#tableBodyRas tr').forEach(tr => {
                let btn = tr.querySelector('.btn-edit-ras');
                if (btn && btn.getAttribute('data-tipe') == tipe.id) {
                    let badge = tr.querySelector('.col-tipe .badge');
                    if (badge) badge.innerText = tipe.nama_jenis;
                }
            });
        });

        document.addEventListener('tipeTernakDeleted', function (e) {
            const tipeId = e.detail.id;
            const selectTambah = document.getElementById('tipe_ternak_id');
            const selectEdit = document.getElementById('edit_tipe_ternak_id');

            if (selectTambah) {
                let opt = selectTambah.querySelector(`option[value="${tipeId}"]`);
                if (opt) opt.remove();
            }
            if (selectEdit) {
                let opt = selectEdit.querySelector(`option[value="${tipeId}"]`);
                if (opt) opt.remove();
            }
        });
    });

</script>
@endpush
