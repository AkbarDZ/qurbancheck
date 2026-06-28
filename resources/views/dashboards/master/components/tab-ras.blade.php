<div class="tab-pane fade p-4" id="ras-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-tag-fill me-2 text-primary"></i>Data Ras Ternak</h5>
            <p class="text-muted small mb-0">Kelola spesifikasi ras/keturunan hewan qurban berdasarkan tipenya.</p>
        </div>
        <button class="btn btn btn-primary px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRas">
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
            <form id="formTambahRas" action="{{ url('/master/ras') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Ras Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Ternak</label>
                        <select class="form-select @error('tipe_ternak_id') is-invalid @enderror" id="tipe_ternak_id" name="tipe_ternak_id" required>
                            <option value="">Pilih Tipe Ternak...</option>
                            @foreach($tipeTernaks as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_ternak_id') == $tipe->id ? 'selected' : '' }}>{{ $tipe->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tipe_ternak_id" style="font-size: 0.75rem;">
                            @error('tipe_ternak_id') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Ras</label>
                        <input type="text" class="form-control @error('nama_ras') is-invalid @enderror" id="nama_ras" name="nama_ras" value="{{ old('nama_ras') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nama_ras" style="font-size: 0.75rem;">
                            @error('nama_ras') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_deskripsi" style="font-size: 0.75rem;">
                            @error('deskripsi') {{ $message }} @enderror
                        </div>
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
            <form id="formEditRas" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Ras Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_ras" name="id_ras" value="{{ old('id_ras') }}">

                    <div class="mb-3">
                        <label class="form-label">Tipe Ternak</label>
                        <select class="form-select @error('tipe_ternak_id') is-invalid @enderror" id="edit_tipe_ternak_id" name="tipe_ternak_id" required>
                            <option value="">Pilih Tipe Ternak...</option>
                            @foreach($tipeTernaks as $tipe)
                            <option value="{{ $tipe->id }}" {{ old('tipe_ternak_id') == $tipe->id ? 'selected' : '' }}>{{ $tipe->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_tipe_ternak_id" style="font-size: 0.75rem;">
                            @error('tipe_ternak_id') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Ras</label>
                        <input type="text" class="form-control @error('nama_ras') is-invalid @enderror" id="edit_nama_ras" name="nama_ras" value="{{ old('nama_ras') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nama_ras" style="font-size: 0.75rem;">
                            @error('nama_ras') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_deskripsi" style="font-size: 0.75rem;">
                            @error('deskripsi') {{ $message }} @enderror
                        </div>
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
        // AJAX submit removed. Form submits normally using standard HTTP POST.

        // edit
        const formEditRas = document.getElementById('formEditRas');
        const modalEditRasInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditRas'));

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
            formEditRas.action = `/master/ras/${id}`;
            formEditRas.submit();
        });

        // ====== FITUR HAPUS TIPE TERNAK ====== //

        tableBodyRas.addEventListener('click', function (e) {
            let btnDelete = e.target.closest('.btn-delete-ras');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                Swal.fire({
                    title: 'Hapus Ras?',
                    text: "Apakah Anda yakin ingin menghapus ras ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let deleteForm = document.getElementById('formDeleteRas');
                        if (!deleteForm) {
                            deleteForm = document.createElement('form');
                            deleteForm.id = 'formDeleteRas';
                            deleteForm.method = 'POST';
                            deleteForm.style.display = 'none';
                            deleteForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(deleteForm);
                        }
                        deleteForm.action = `/master/ras/${id}`;
                        deleteForm.submit();
                    }
                });
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
