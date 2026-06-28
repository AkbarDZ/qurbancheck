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
            <form id="formTambahKriteria" action="{{ url('/master/kriteria') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Kriteria Syariat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" id="nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nama_kriteria" style="font-size: 0.75rem;">
                            @error('nama_kriteria') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi_kriteria" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_deskripsi_kriteria" style="font-size: 0.75rem;">
                            @error('deskripsi') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_fatal" name="is_fatal" value="1" {{ old('is_fatal') ? 'checked' : '' }}>
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
            <form id="formEditKriteria" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Kriteria Syariat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_kriteria" name="id_kriteria" value="{{ old('id_kriteria') }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" id="edit_nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nama_kriteria" style="font-size: 0.75rem;">
                            @error('nama_kriteria') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi_kriteria" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_deskripsi_kriteria" style="font-size: 0.75rem;">
                            @error('deskripsi') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_fatal" name="is_fatal" value="1" {{ old('is_fatal') ? 'checked' : '' }}>
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
        // AJAX submit removed. Form submits normally using standard HTTP POST.

        // edit
        const formEditKriteria = document.getElementById('formEditKriteria');
        const modalEditKriteriaInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditKriteria'));

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
            formEditKriteria.action = `/master/kriteria/${id}`;
            formEditKriteria.submit();
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
                        let deleteForm = document.getElementById('formDeleteKriteria');
                        if (!deleteForm) {
                            deleteForm = document.createElement('form');
                            deleteForm.id = 'formDeleteKriteria';
                            deleteForm.method = 'POST';
                            deleteForm.style.display = 'none';
                            deleteForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(deleteForm);
                        }
                        deleteForm.action = `/master/kriteria/${id}`;
                        deleteForm.submit();
                    }
                });
            }
        });
    });

</script>
@endpush
