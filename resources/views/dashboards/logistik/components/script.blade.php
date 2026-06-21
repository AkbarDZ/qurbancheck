@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // --- 1. LOGIKA AUTO-KALKULASI TOTAL BIAYA ---
        const selectPakan = document.getElementById('selectPakan');
        const inputJumlah = document.getElementById('inputJumlah');
        const labelHarga = document.getElementById('labelHargaKg');
        const labelTotal = document.getElementById('labelTotalBiaya');

        let currentHargaPerKg = 0;

        // Format Rupiah Standar
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Fungsi Kalkulasi
        function hitungTotal() {
            let jumlah = parseFloat(inputJumlah.value) || 0;
            let total = currentHargaPerKg * jumlah;
            labelTotal.innerText = formatRupiah(total);
        }

        // Listener saat Dropdown Pakan diubah
        if (selectPakan) {
            selectPakan.addEventListener('change', function () {
                let selectedOption = this.options[this.selectedIndex];
                currentHargaPerKg = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                labelHarga.innerText = formatRupiah(currentHargaPerKg);
                hitungTotal();
            });
        }

        // Listener saat input Jumlah diketik
        if (inputJumlah) {
            inputJumlah.addEventListener('input', hitungTotal);
        }


        // --- 2. LOGIKA AJAX SUBMIT DISTRIBUSI ---
        const formDistribusi = document.getElementById('formDistribusi');
        const btnSimpan = document.getElementById('btnSimpanDistribusi');
        const errorAlert = document.getElementById('error_distribusi');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (formDistribusi) {
            formDistribusi.addEventListener('submit', function (e) {
                e.preventDefault();

                errorAlert.classList.add('d-none');
                let originalText = btnSimpan.innerHTML;
                btnSimpan.innerHTML =
                    '<span class="spinner-border spinner-border-sm"></span> Memproses...';
                btnSimpan.disabled = true;

                const formData = new FormData(formDistribusi);

                fetch('/logistik/distribusi', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(async response => {
                        const isJson = response.headers.get('content-type')?.includes('application/json');
                        if (!response.ok) {
                            let errorMsg = 'Terjadi kesalahan sistem.';
                            if (isJson) {
                                const errData = await response.json();
                                errorMsg = errData.message || Object.values(errData.errors)[0][0] || errorMsg;
                            }
                            return Promise.reject(errorMsg);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Refresh halaman untuk mengupdate stok gudang dan riwayat tabel
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        errorAlert.innerText = error;
                        errorAlert.classList.remove('d-none');
                    })
                    .finally(() => {
                        btnSimpan.innerHTML = originalText;
                        btnSimpan.disabled = false;
                    });
            });
        }

        // --- 3. LOGIKA CLIENT-SIDE PAGINATION ---
        window.initTablePagination = function (tableBodyId, paginationId, itemsPerPage = 4) {
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

                    // Update Nomor
                    const firstTd = row.querySelector('td:first-child');
                    if (firstTd && !isNaN(parseInt(firstTd.innerText))) {
                        firstTd.innerText = index + 1;
                    }
                });

                paginationContainer.innerHTML = '';
                
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
        };

        window.initTablePagination('tbodyGudang', 'paginationGudang', 4);
        window.initTablePagination('tbodyRiwayat', 'paginationRiwayat', 4);

    });
</script>
@endpush
