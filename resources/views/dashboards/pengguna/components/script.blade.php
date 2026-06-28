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
    // AJAX submit removed. Form submits normally using standard HTTP POST.

    // ====== EDIT PENGGUNA ====== //
    const formEdit = document.getElementById('formEditPengguna');
    const btnUpdate = document.getElementById('btnUpdatePengguna');
    const modalEditInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditPengguna'));

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
        formEdit.action = `/pengguna/${id}`;
        formEdit.submit();
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
                    let deleteForm = document.getElementById('formDeletePengguna');
                    if (!deleteForm) {
                        deleteForm = document.createElement('form');
                        deleteForm.id = 'formDeletePengguna';
                        deleteForm.method = 'POST';
                        deleteForm.style.display = 'none';
                        deleteForm.innerHTML = `
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;
                        document.body.appendChild(deleteForm);
                    }
                    deleteForm.action = `/pengguna/${id}`;
                    deleteForm.submit();
                }
            });
        }
    });

    // Auto-open modal if validation fails
    @if ($errors->any())
        @if (old('_method') === 'PUT')
            const editId = "{{ old('id_pengguna') }}";
            const row = document.getElementById(`row-pengguna-${editId}`);
            if (row) {
                const btnEdit = row.querySelector('.btn-edit-pengguna');
                if (btnEdit) {
                    btnEdit.click();
                }
            }
        @else
            const modalTambah = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahPengguna'));
            if (modalTambah) {
                modalTambah.show();
            }
        @endif
    @endif
});
</script>
@endpush
