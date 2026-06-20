<div class="tab-pane fade show active p-4" id="tipe-pane" role="tabpanel" tabindex="0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-tags-fill me-2 text-primary"></i>Data Jenis Ternak</h5>
            <p class="text-muted small mb-0">Kelola kategori jenis hewan ternak, batas minimal umur kelayakan qurban.</p>
        </div>
        <button class="btn btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTipe">
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
            <form id="formTambahTipe">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahTipeLabel">Tambah Tipe Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_jenis" class="form-label">Nama Jenis</label>
                        <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" required>
                        <div class="invalid-feedback" id="error_nama_jenis"></div>
                    </div>
                    <div class="mb-3">
                        <label for="umur_minimal_qurban" class="form-label">Umur Minimal Qurban (Bulan)</label>
                        <input type="number" class="form-control" id="umur_minimal_qurban" name="umur_minimal_qurban"
                            required>
                        <div class="invalid-feedback" id="error_umur_minimal_qurban"></div>
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
            <form id="formEditTipe">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Tipe Ternak</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_tipe" name="id">

                    <div class="mb-3">
                        <label class="form-label">Nama Jenis</label>
                        <input type="text" class="form-control" id="edit_nama_jenis" name="nama_jenis" required>
                        <div class="invalid-feedback" id="error_edit_nama_jenis"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Umur Minimal Qurban (Bulan)</label>
                        <input type="number" class="form-control" id="edit_umur_minimal_qurban"
                            name="umur_minimal_qurban" required>
                        <div class="invalid-feedback" id="error_edit_umur_minimal_qurban"></div>
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
        const btnSimpan = document.getElementById('btnSimpanTipe');
        const loadingIcon = document.getElementById('loadingTipe');

        // Ambil CSRF Token dari Meta Tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        formTipe.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            // UX: Ubah tombol jadi loading state
            btnSimpan.disabled = true;
            loadingIcon.classList.remove('d-none');

            // Reset error message sebelumnya
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Siapkan data form
            const formData = new FormData(formTipe);

            // Lakukan request AJAX menggunakan Fetch API
            fetch('/master/tipe', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' // Penting agar Laravel membalas dengan JSON saat validasi gagal
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let emptyRow = tableBody.querySelector('td[colspan]');
                        if (emptyRow && (emptyRow.innerText.toLowerCase().includes('kosong') || emptyRow.innerText.toLowerCase().includes('belum ada'))) {
                            emptyRow.parentElement.remove(); // Hapus elemen <tr> pembungkusnya
                        }
                        // 1. Sisipkan baris baru ke dalam tabel tanpa reload
                        // Catatan: Variabel 'data.data' berisi object model dari Controller
                        let tr = document.createElement('tr');
                        // Tambahkan ID pada row baru ini
                        tr.id = `row-tipe-${data.data.id}`;
                        tr.innerHTML = `
                            <td class="py-3 px-3 fw-semibold text-secondary">Baru</td>
                            <td class="py-3 px-3 col-nama fw-bold text-dark">${data.data.nama_jenis}</td>
                            <td class="py-3 px-3 col-umur">
                                <span class="fw-semibold text-dark">${data.data.umur_minimal_qurban}</span> <span class="text-muted small">bulan</span>
                            </td>
                            <td class="py-3 px-3 text-end">
                                <button class="btn btn-sm btn-outline-secondary btn-edit-tipe" 
                                    data-id="${data.data.id}" 
                                    data-nama="${data.data.nama_jenis}" 
                                    data-umur="${data.data.umur_minimal_qurban}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Jenis">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete-tipe" data-id="${data.data.id}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Jenis">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.insertAdjacentElement('afterbegin',
                            tr); // Masukkan di urutan paling atas
                        if (window.tipePagination) window.tipePagination.update();

                        // 2. Tutup Modal Bootstrap
                        let modalEl = document.getElementById('modalTambahTipe');
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();

                        // 3. Reset isi Form
                        formTipe.reset();

                        // Opsional: Tampilkan toast/alert sukses di sini
                        alert(data.message);

                        // Trigger custom event agar komponen lain (seperti tab-ras) bisa update dropdown
                        document.dispatchEvent(new CustomEvent('tipeTernakAdded', {
                            detail: {
                                id: data.data.id,
                                nama_jenis: data.data.nama_jenis
                            }
                        }));
                    } else if (data.message) {
                        // Tangkap error validasi dari Laravel (Status Code 422)
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
                    alert('Terjadi kesalahan pada server.');
                })
                .finally(() => {
                    // Kembalikan tombol ke keadaan semula
                    btnSimpan.disabled = false;
                    loadingIcon.classList.add('d-none');
                });
        });



        // edit
        // ====== FITUR EDIT TIPE TERNAK ====== //

        const formEditTipe = document.getElementById('formEditTipe');
        const btnUpdateTipe = document.getElementById('btnUpdateTipe');
        const loadingEditTipe = document.getElementById('loadingEditTipe');
        const modalEditInstance = new bootstrap.Modal(document.getElementById('modalEditTipe'));

        // 1. Event Delegation untuk membuka Modal Edit
        // Kita memasang event di tableBody agar row yang baru ditambah via AJAX juga bisa langsung diedit
        tableBody.addEventListener('click', function (e) {
            // Cari elemen terdekat yang memiliki class .btn-edit-tipe
            let btnEdit = e.target.closest('.btn-edit-tipe');

            if (btnEdit) {
                // Ambil data dari atribut tombol
                let id = btnEdit.getAttribute('data-id');
                let nama = btnEdit.getAttribute('data-nama');
                let umur = btnEdit.getAttribute('data-umur');

                // Isi form edit
                document.getElementById('edit_id_tipe').value = id;
                document.getElementById('edit_nama_jenis').value = nama;
                document.getElementById('edit_umur_minimal_qurban').value = umur;

                // Buka modal
                modalEditInstance.show();
            }
        });

        // 2. Submit Form Edit via AJAX
        formEditTipe.addEventListener('submit', function (e) {
            e.preventDefault();

            let id = document.getElementById('edit_id_tipe').value;

            btnUpdateTipe.disabled = true;
            loadingEditTipe.classList.remove('d-none');
            document.querySelectorAll('#formEditTipe .is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));

            const formData = new FormData(formEditTipe);
            // Trik penting di Laravel: Kirim POST tapi beri tahu Laravel bahwa ini adalah PUT request
            formData.append('_method', 'PUT');

            fetch(`/master/tipe/${id}`, {
                    method: 'POST', // Gunakan POST untuk FormData
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update tampilan baris di tabel tanpa reload
                        let tr = document.getElementById(`row-tipe-${id}`);
                        tr.querySelector('.col-nama').innerText = data.data.nama_jenis;
                        
                        let colUmur = tr.querySelector('.col-umur');
                        if (colUmur) {
                            colUmur.innerHTML = `<span class="fw-semibold text-dark">${data.data.umur_minimal_qurban}</span> <span class="text-muted small">bulan</span>`;
                        }

                        // Update juga atribut data-* pada tombol editnya agar jika diklik lagi datanya sudah baru
                        let btnEdit = tr.querySelector('.btn-edit-tipe');
                        btnEdit.setAttribute('data-nama', data.data.nama_jenis);
                        btnEdit.setAttribute('data-umur', data.data.umur_minimal_qurban);

                        modalEditInstance.hide();
                        alert(data.message);

                        // Trigger custom event agar komponen lain (seperti tab-ras) bisa update dropdown
                        document.dispatchEvent(new CustomEvent('tipeTernakUpdated', {
                            detail: {
                                id: id,
                                nama_jenis: data.data.nama_jenis
                            }
                        }));
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
                    alert('Terjadi kesalahan saat mengupdate data.');
                })
                .finally(() => {
                    btnUpdateTipe.disabled = false;
                    loadingEditTipe.classList.add('d-none');
                });
        });


        // ====== FITUR HAPUS TIPE TERNAK ====== //

        // Kita tetap menggunakan event delegation pada tableBody
        tableBody.addEventListener('click', function (e) {
            // Cari elemen terdekat yang memiliki class .btn-delete-tipe
            let btnDelete = e.target.closest('.btn-delete-tipe');

            if (btnDelete) {
                let id = btnDelete.getAttribute('data-id');

                // Munculkan dialog konfirmasi
                if (confirm('Apakah Anda yakin ingin menghapus tipe ternak ini?')) {

                    // UX: Ubah tombol jadi status loading agar tidak diklik dua kali
                    let originalIcon = btnDelete.innerHTML;
                    btnDelete.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    btnDelete.disabled = true;

                    // Lakukan request DELETE
                    fetch(`/master/tipe/${id}`, {
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
                                let tr = document.getElementById(`row-tipe-${id}`);
                                if (tr) {
                                    // Hapus elemen dari DOM HTML secara langsung
                                    tr.remove();
                                }
                                if (window.tipePagination) window.tipePagination.update();

                                // 2. Cek apakah tabel sekarang kosong, jika ya, tampilkan pesan kosong
                                if (tableBody.querySelectorAll('tr').length === 0) {
                                    tableBody.innerHTML =
                                        '<tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i><h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Tipe Ternak</h6><p class="small text-muted mb-0">Klik tombol "Tambah Tipe" untuk mendaftarkan kategori baru.</p></td></tr>';
                                }

                                alert(data.message);

                                // Trigger custom event agar dropdown di tab-ras juga bisa ikut update
                                document.dispatchEvent(new CustomEvent('tipeTernakDeleted', {
                                    detail: {
                                        id: id
                                    }
                                }));
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
