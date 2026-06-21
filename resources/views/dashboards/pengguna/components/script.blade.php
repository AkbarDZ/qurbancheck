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

            Swal.fire({
                title: 'Hapus Pengguna?',
                text: "Apakah Anda yakin ingin menghapus pengguna ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D9534F',
                cancelButtonColor: '#6C757D',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
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

                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#428475'
                            });

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
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#428475'
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            icon: 'error',
                            confirmButtonColor: '#428475'
                        });
                    });
                }
            });
        }
    });
});
</script>
@endpush
