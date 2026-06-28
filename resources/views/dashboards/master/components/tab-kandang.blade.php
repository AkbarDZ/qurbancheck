<div class="tab-pane fade p-4" id="kandang-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-house-door me-2 text-primary"></i>Data Kandang</h5>
            <p class="text-muted small mb-0">Kelola kandang ternak, kapasitas maksimal, dan status keterisian.</p>
        </div>
        <button class="btn btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKandang">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kandang
        </button>
    </div>
    
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr class="border-bottom border-light-subtle">
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-door-closed me-2 text-muted"></i>Nama Kandang</th>
                    <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-bar-chart-steps me-2 text-muted"></i>Kapasitas Maksimal</th>
                    <th class="py-3 px-3 text-muted fw-bold text-center" style="font-size: 0.85rem; width: 150px;"><i class="bi bi-info-circle me-2 text-muted"></i>Status</th>
                    <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBodyKandang">
                @forelse($kandangs as $index => $kandang)
                <tr id="row-kandang-{{ $kandang->id }}">
                    <td class="py-3 px-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                    <td class="py-3 px-3 col-nama fw-bold text-dark">{{ $kandang->nama_kandang }}</td>
                    <td class="py-3 px-3 col-kapasitas">
                        <span class="fw-semibold text-dark">{{ $kandang->kapasitas_maksimal }}</span> <span class="text-muted small">ekor</span>
                    </td>
                    <td class="py-3 px-3 text-center col-status">
                        @if(($kandang->ternaks_count ?? 0) >= ($kandang->kapasitas_maksimal ?? 0))
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Penuh</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Tersedia</span>
                        @endif
                    </td>
                    <td class="py-3 px-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-kandang" data-id="{{ $kandang->id }}"
                            data-nama="{{ $kandang->nama_kandang }}"
                            data-kapasitas="{{ $kandang->kapasitas_maksimal }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Kandang"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-kandang" data-id="{{ $kandang->id }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Kandang"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Kandang</h6>
                        <p class="small text-muted mb-0">Klik tombol "Tambah Kandang" untuk mendaftarkan kandang baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Container -->
    <div id="paginationKandang" class="d-flex justify-content-center mt-3"></div>
</div>

<div class="modal fade" id="modalTambahKandang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahKandang" action="{{ url('/master/kandang') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Kandang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kandang</label>
                        <input type="text" class="form-control @error('nama_kandang') is-invalid @enderror" id="nama_kandang" name="nama_kandang" value="{{ old('nama_kandang') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_nama_kandang" style="font-size: 0.75rem;">
                            @error('nama_kandang') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Maksimal</label>
                        <input type="number" class="form-control @error('kapasitas_maksimal') is-invalid @enderror" id="kapasitas_maksimal" name="kapasitas_maksimal"
                            value="{{ old('kapasitas_maksimal') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_kapasitas_maksimal" style="font-size: 0.75rem;">
                            @error('kapasitas_maksimal') {{ $message }} @enderror
                        </div>
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
            <form id="formEditKandang" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Kandang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_kandang" name="id_kandang" value="{{ old('id_kandang') }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Kandang</label>
                        <input type="text" class="form-control @error('nama_kandang') is-invalid @enderror" id="edit_nama_kandang" name="nama_kandang" value="{{ old('nama_kandang') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_nama_kandang" style="font-size: 0.75rem;">
                            @error('nama_kandang') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Maksimal</label>
                        <input type="number" class="form-control @error('kapasitas_maksimal') is-invalid @enderror" id="edit_kapasitas_maksimal" name="kapasitas_maksimal"
                            value="{{ old('kapasitas_maksimal') }}" required>
                        <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_edit_kapasitas_maksimal" style="font-size: 0.75rem;">
                            @error('kapasitas_maksimal') {{ $message }} @enderror
                        </div>
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
        window.kandangPagination = window.initTablePagination('tableBodyKandang', 'paginationKandang', 5);

        // --- Handler Form Kandang ---
        // AJAX submit removed. Form submits normally using standard HTTP POST.

        // edit
        const formEditKandang = document.getElementById('formEditKandang');
        const modalEditKandangInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditKandang'));

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
            formEditKandang.action = `/master/kandang/${id}`;
            formEditKandang.submit();
        });
        // ====== FITUR HAPUS KANDANG ====== //
        tableBodyKandang.addEventListener('click', function (e) {
            let btnDelete = e.target.closest('.btn-delete-kandang');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                Swal.fire({
                    title: 'Hapus Kandang?',
                    text: "Apakah Anda yakin ingin menghapus kandang ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D9534F',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let deleteForm = document.getElementById('formDeleteKandang');
                        if (!deleteForm) {
                            deleteForm = document.createElement('form');
                            deleteForm.id = 'formDeleteKandang';
                            deleteForm.method = 'POST';
                            deleteForm.style.display = 'none';
                            deleteForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(deleteForm);
                        }
                        deleteForm.action = `/master/kandang/${id}`;
                        deleteForm.submit();
                    }
                });
            }
        });
    });

</script>
@endpush
