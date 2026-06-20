@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Sistem Qurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-dark">Manajemen Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun pengguna sistem, perbarui hak akses dan data login.</p>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Pengguna</h5>
            </div>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white mx-4 mb-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr class="border-bottom border-light-subtle">
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-person-fill me-2 text-muted"></i>Nama</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-envelope-fill me-2 text-muted"></i>Email</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-shield-lock-fill me-2 text-muted"></i>Role</th>
                        <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBodyPengguna">
                    @forelse($users as $index => $user)
                    <tr id="row-pengguna-{{ $user->id }}">
                        <td class="py-3 px-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                        <td class="py-3 px-3 col-name fw-bold text-dark">{{ $user->name }}</td>
                        <td class="py-3 px-3 col-email fw-semibold text-secondary">{{ $user->email }}</td>
                        <td class="py-3 px-3 col-role">
                            @if($user->role === 'owner/admin')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-shield-fill-check me-1"></i> Owner/Admin
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-person me-1"></i> Pekerja
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-pengguna" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pengguna">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-pengguna" data-id="{{ $user->id }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Pengguna">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-3 text-muted opacity-50"></i>
                            <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Pengguna</h6>
                            <p class="small text-muted mb-0">Klik tombol "Tambah Pengguna" untuk mendaftarkan user baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination Container -->
        <div id="paginationPengguna" class="d-flex justify-content-center mt-2 pb-4"></div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahPengguna" tabindex="-1" aria-labelledby="modalTambahPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahPengguna">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPenggunaLabel"><i class="bi bi-person-plus-fill me-2 text-success"></i>Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                        <div class="invalid-feedback" id="error_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@qurban.com" required>
                        <div class="invalid-feedback" id="error_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role Akses</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="owner/admin">Owner/Admin</option>
                            <option value="pekerja">Pekerja</option>
                        </select>
                        <div class="invalid-feedback" id="error_role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        <div class="invalid-feedback" id="error_password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanPengguna">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingTambah" role="status" aria-hidden="true"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditPengguna" tabindex="-1" aria-labelledby="modalEditPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditPengguna">
                <input type="hidden" id="edit_id_pengguna">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPenggunaLabel"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback" id="error_edit_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback" id="error_edit_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role Akses</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="owner/admin">Owner/Admin</option>
                            <option value="pekerja">Pekerja</option>
                        </select>
                        <div class="invalid-feedback" id="error_edit_role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru <span class="text-muted small">(Opsional)</span></label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Biarkan kosong jika tidak ingin diubah">
                        <div class="invalid-feedback" id="error_edit_password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdatePengguna">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEdit" role="status" aria-hidden="true"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.initPenggunaTooltips = function (context = document) {
    let triggers = context.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...triggers].forEach(el => {
        let instance = bootstrap.Tooltip.getInstance(el);
        if (instance) instance.dispose();
        new bootstrap.Tooltip(el);
    });
};

window.initTablePagination = function (tableBodyId, paginationId, itemsPerPage = 5) {
    const tbody = document.getElementById(tableBodyId);
    const paginationContainer = document.getElementById(paginationId);
    if (!tbody || !paginationContainer) return null;

    let currentPage = 1;

    function render() {
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => {
            return !tr.querySelector('td[colspan]');
        });

        const totalItems = rows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        rows.forEach((row, index) => {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            if (index >= start && index < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

            const firstTd = row.querySelector('td:first-child');
            if (firstTd && !isNaN(parseInt(firstTd.innerText))) {
                firstTd.innerText = index + 1;
            }
        });

        paginationContainer.innerHTML = '';
        
        if (window.initPenggunaTooltips) {
            window.initPenggunaTooltips(tbody);
        }

        if (totalPages <= 1) {
            return;
        }

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0 gap-1';

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        const prevA = document.createElement('a');
        prevA.className = 'page-link rounded-2';
        prevA.href = '#';
        prevA.innerHTML = '&laquo;';
        prevA.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                render();
            }
        });
        prevLi.appendChild(prevA);
        ul.appendChild(prevLi);

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${currentPage === i ? 'active' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link rounded-2';
            a.href = '#';
            a.innerText = i;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                render();
            });
            li.appendChild(a);
            ul.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        const nextA = document.createElement('a');
        nextA.className = 'page-link rounded-2';
        nextA.href = '#';
        nextA.innerHTML = '&raquo;';
        nextA.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                render();
            }
        });
        nextLi.appendChild(nextA);
        ul.appendChild(nextLi);

        nav.appendChild(ul);
        paginationContainer.appendChild(nav);
    }

    render();

    return {
        update: function () {
            render();
        },
        setCurrentPage: function (page) {
            currentPage = page;
            render();
        }
    };
};

document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const tableBody = document.getElementById('tableBodyPengguna');

    // Initialize pagination
    window.penggunaPagination = window.initTablePagination('tableBodyPengguna', 'paginationPengguna', 5);
    window.initPenggunaTooltips();

    // ====== TAMBAH PENGGUNA ====== //
    const formTambah = document.getElementById('formTambahPengguna');
    const btnSimpan = document.getElementById('btnSimpanPengguna');
    const loadingTambah = document.getElementById('loadingTambah');

    formTambah.addEventListener('submit', function (e) {
        e.preventDefault();

        btnSimpan.disabled = true;
        loadingTambah.classList.remove('d-none');
        
        // Reset validation errors
        document.querySelectorAll('#formTambahPengguna .is-invalid').forEach(el => el.classList.remove('is-invalid'));

        const formData = new FormData(formTambah);

        fetch("{{ route('pengguna.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.status === 422) {
                return response.json().then(data => {
                    for (const [key, messages] of Object.entries(data.errors || {})) {
                        let inputEl = document.getElementById(key);
                        let errorEl = document.getElementById(`error_${key}`);
                        if (inputEl && errorEl) {
                            inputEl.classList.add('is-invalid');
                            errorEl.innerText = messages[0];
                        }
                    }
                    throw new Error('Validation failed');
                });
            }
            if (!response.ok) throw new Error('Server error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove empty state if any
                const emptyRow = tableBody.querySelector('td[colspan]');
                if (emptyRow) {
                    tableBody.innerHTML = '';
                }

                // Add new row to table
                const newIndex = tableBody.querySelectorAll('tr').length + 1;
                const roleBadge = data.data.role === 'owner/admin' 
                    ? `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold"><i class="bi bi-shield-fill-check me-1"></i> Owner/Admin</span>`
                    : `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold"><i class="bi bi-person me-1"></i> Pekerja</span>`;

                const newRow = document.createElement('tr');
                newRow.id = `row-pengguna-${data.data.id}`;
                newRow.innerHTML = `
                    <td class="py-3 px-3 fw-semibold text-secondary">${newIndex}</td>
                    <td class="py-3 px-3 col-name fw-bold text-dark">${data.data.name}</td>
                    <td class="py-3 px-3 col-email fw-semibold text-secondary">${data.data.email}</td>
                    <td class="py-3 px-3 col-role">${roleBadge}</td>
                    <td class="py-3 px-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary btn-edit-pengguna" data-id="${data.data.id}"
                            data-name="${data.data.name}" data-email="${data.data.email}" data-role="${data.data.role}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pengguna">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-pengguna" data-id="${data.data.id}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Pengguna">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(newRow);

                // Update pagination
                if (window.penggunaPagination) window.penggunaPagination.update();

                // Close Modal
                bootstrap.Modal.getInstance(document.getElementById('modalTambahPengguna')).hide();
                formTambah.reset();
                alert(data.message);
            }
        })
        .catch(error => {
            if (error.message !== 'Validation failed') {
                console.error(error);
                alert('Terjadi kesalahan pada server.');
            }
        })
        .finally(() => {
            btnSimpan.disabled = false;
            loadingTambah.classList.add('d-none');
        });
    });

    // ====== EDIT PENGGUNA ====== //
    const formEdit = document.getElementById('formEditPengguna');
    const btnUpdate = document.getElementById('btnUpdatePengguna');
    const loadingEdit = document.getElementById('loadingEdit');
    const modalEditInstance = new bootstrap.Modal(document.getElementById('modalEditPengguna'));

    tableBody.addEventListener('click', function (e) {
        let btnEdit = e.target.closest('.btn-edit-pengguna');

        if (btnEdit) {
            let id = btnEdit.getAttribute('data-id');
            let name = btnEdit.getAttribute('data-name');
            let email = btnEdit.getAttribute('data-email');
            let role = btnEdit.getAttribute('data-role');

            document.getElementById('edit_id_pengguna').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_password').value = '';

            // Clear validation error styling
            document.querySelectorAll('#formEditPengguna .is-invalid').forEach(el => el.classList.remove('is-invalid'));

            modalEditInstance.show();
        }
    });

    formEdit.addEventListener('submit', function (e) {
        e.preventDefault();

        let id = document.getElementById('edit_id_pengguna').value;

        btnUpdate.disabled = true;
        loadingEdit.classList.remove('d-none');
        
        document.querySelectorAll('#formEditPengguna .is-invalid').forEach(el => el.classList.remove('is-invalid'));

        const formData = new FormData(formEdit);
        formData.append('_method', 'PUT');

        fetch(`/pengguna/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.status === 422) {
                return response.json().then(data => {
                    for (const [key, messages] of Object.entries(data.errors || {})) {
                        let inputEl = document.getElementById(`edit_${key}`);
                        let errorEl = document.getElementById(`error_edit_${key}`);
                        if (inputEl && errorEl) {
                            inputEl.classList.add('is-invalid');
                            errorEl.innerText = messages[0];
                        }
                    }
                    throw new Error('Validation failed');
                });
            }
            if (!response.ok) throw new Error('Server error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`row-pengguna-${id}`);
                if (row) {
                    row.querySelector('.col-name').innerText = data.data.name;
                    row.querySelector('.col-email').innerText = data.data.email;
                    
                    const roleBadge = data.data.role === 'owner/admin' 
                        ? `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold"><i class="bi bi-shield-fill-check me-1"></i> Owner/Admin</span>`
                        : `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold"><i class="bi bi-person me-1"></i> Pekerja</span>`;
                    
                    row.querySelector('.col-role').innerHTML = roleBadge;

                    // Update data attributes of edit button
                    const btnEdit = row.querySelector('.btn-edit-pengguna');
                    btnEdit.setAttribute('data-name', data.data.name);
                    btnEdit.setAttribute('data-email', data.data.email);
                    btnEdit.setAttribute('data-role', data.data.role);
                }

                modalEditInstance.hide();
                alert(data.message);
            }
        })
        .catch(error => {
            if (error.message !== 'Validation failed') {
                console.error(error);
                alert('Terjadi kesalahan pada server.');
            }
        })
        .finally(() => {
            btnUpdate.disabled = false;
            loadingEdit.classList.add('d-none');
        });
    });

    // ====== HAPUS PENGGUNA ====== //
    tableBody.addEventListener('click', function (e) {
        let btnDelete = e.target.closest('.btn-delete-pengguna');

        if (btnDelete) {
            let id = btnDelete.getAttribute('data-id');

            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                fetch(`/pengguna/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById(`row-pengguna-${id}`);
                        if (row) row.remove();

                        // Update pagination
                        if (window.penggunaPagination) window.penggunaPagination.update();

                        alert(data.message);

                        // If table is now empty, render empty state
                        if (tableBody.querySelectorAll('tr').length === 0) {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-2 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Pengguna</h6>
                                        <p class="small text-muted mb-0">Klik tombol "Tambah Pengguna" untuk mendaftarkan user baru.</p>
                                    </td>
                                </tr>
                            `;
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        }
    });
});
</script>
@endpush
