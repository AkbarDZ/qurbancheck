<div class="tab-pane fade show active p-4" id="tipe-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-tags-fill me-2 text-primary"></i>Data Jenis Ternak</h5>
            <p class="text-muted small mb-0">Kelola kategori jenis hewan ternak, batas minimal umur kelayakan qurban.</p>
        </div>
        <button class="btn btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTipe">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis
        </button>
    </div>
    
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr class="border-bottom border-light-subtle">
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-tag-fill me-2 text-muted"></i>Nama Jenis</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-calendar-event me-2 text-muted"></i>Umur Minimal Qurban</th>
                    <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBodyTipe">
                @forelse($tipeTernaks as $index => $tipe)
                <tr id="row-tipe-{{ $tipe->id }}">
                    <td class="py-3 px-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                    <td class="py-3 px-3 col-nama fw-bold text-dark">{{ $tipe->nama_jenis }}</td>
                    <td class="py-3 px-3 col-umur">
                        <span class="fw-semibold text-dark">{{ $tipe->umur_minimal_qurban }}</span> <span class="text-muted small">bulan</span>
                    </td>
                    <td class="py-3 px-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-tipe" data-id="{{ $tipe->id }}"
                            data-nama="{{ $tipe->nama_jenis }}" data-umur="{{ $tipe->umur_minimal_qurban }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Jenis">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-tipe" data-id="{{ $tipe->id }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Jenis">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Tipe Ternak</h6>
                        <p class="small text-muted mb-0">Klik tombol "Tambah Tipe" untuk mendaftarkan kategori baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Container -->
    <div id="paginationTipe" class="d-flex justify-content-center mt-3"></div>
</div>

{{-- // modal tambah --}}
<div class="modal fade" id="modalTambahTipe" tabindex="-1" aria-labelledby="modalTambahTipeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahTipe" action="{{ url('/master/tipe') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahTipeLabel">Tambah Tipe Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_jenis" class="form-label">Nama Jenis</label>
                        <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" id="nama_jenis" name="nama_jenis" value="{{ old('nama_jenis') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nama_jenis" style="font-size: 0.75rem;">
                            @error('nama_jenis') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="umur_minimal_qurban" class="form-label">Umur Minimal Qurban (Bulan)</label>
                        <input type="number" class="form-control @error('umur_minimal_qurban') is-invalid @enderror" id="umur_minimal_qurban" name="umur_minimal_qurban"
                            value="{{ old('umur_minimal_qurban') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_umur_minimal_qurban" style="font-size: 0.75rem;">
                            @error('umur_minimal_qurban') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanTipe">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingTipe" role="status"
                            aria-hidden="true"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- // modal edit --}}
<div class="modal fade" id="modalEditTipe" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditTipe" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Tipe Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_tipe" name="id_tipe" value="{{ old('id_tipe') }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Jenis</label>
                        <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" id="edit_nama_jenis" name="nama_jenis" value="{{ old('nama_jenis') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nama_jenis" style="font-size: 0.75rem;">
                            @error('nama_jenis') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Umur Minimal Qurban (Bulan)</label>
                        <input type="number" class="form-control @error('umur_minimal_qurban') is-invalid @enderror" id="edit_umur_minimal_qurban"
                            name="umur_minimal_qurban" value="{{ old('umur_minimal_qurban') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_umur_minimal_qurban" style="font-size: 0.75rem;">
                            @error('umur_minimal_qurban') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateTipe">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEditTipe" role="status"
                            aria-hidden="true"></span>
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
        window.tipePagination = window.initTablePagination('tableBodyTipe', 'paginationTipe', 5);

        const formTipe = document.getElementById('formTambahTipe');
        const tableBody = document.getElementById('tableBodyTipe');

        // Ambil CSRF Token dari Meta Tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // formTipe submit handler - AJAX removed. Form submits normally.

        // edit
        // ====== FITUR EDIT TIPE TERNAK ====== //

        const formEditTipe = document.getElementById('formEditTipe');
        const modalEditInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditTipe'));

        // 1. Event Delegation untuk membuka Modal Edit
        tableBody.addEventListener('click', function (e) {
            let btnEdit = e.target.closest('.btn-edit-tipe');

            if (btnEdit) {
                let id = btnEdit.getAttribute('data-id');
                let nama = btnEdit.getAttribute('data-nama');
                let umur = btnEdit.getAttribute('data-umur');

                document.getElementById('edit_id_tipe').value = id;
                document.getElementById('edit_nama_jenis').value = nama;
                document.getElementById('edit_umur_minimal_qurban').value = umur;

                modalEditInstance.show();
            }
        });

        // 2. Submit Form Edit via standard HTTP PUT
        formEditTipe.addEventListener('submit', function (e) {
            e.preventDefault();
            let id = document.getElementById('edit_id_tipe').value;
            formEditTipe.action = `/master/tipe/${id}`;
            formEditTipe.submit();
        });


        // ====== FITUR HAPUS TIPE TERNAK ====== //

        tableBody.addEventListener('click', function (e) {
            let btnDelete = e.target.closest('.btn-delete-tipe');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                Swal.fire({
                    title: 'Hapus Tipe?',
                    text: "Apakah Anda yakin ingin menghapus tipe ternak ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let deleteForm = document.getElementById('formDeleteTipe');
                        if (!deleteForm) {
                            deleteForm = document.createElement('form');
                            deleteForm.id = 'formDeleteTipe';
                            deleteForm.method = 'POST';
                            deleteForm.style.display = 'none';
                            deleteForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(deleteForm);
                        }
                        deleteForm.action = `/master/tipe/${id}`;
                        deleteForm.submit();
                    }
                });
            }
        });
    });

</script>
@endpush
