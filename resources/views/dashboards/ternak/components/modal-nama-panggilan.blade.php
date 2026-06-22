<div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
    <div class="d-flex align-items-center">
        <h4 class="card-title fw-bold mb-0 text-primary">
            <i class="bi bi-tag-fill me-2"></i><span class="btn-text-responsive">Tag No: </span>{{ $ternak->nomor_eartag }}
        </h4>

        <div class="ms-3 container-nama-panggilan" id="nama-container-{{ $ternak->id }}">
            @if($ternak->nama_panggilan)
            <span class="badge bg-light text-dark border rounded-pill trigger-nama"
                style="cursor: pointer; font-size: 0.85rem;" data-id="{{ $ternak->id }}"
                data-nama="{{ $ternak->nama_panggilan }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Klik untuk ubah/hapus nama">
                "{{ $ternak->nama_panggilan }}" <i class="bi bi-pencil-square ms-1 text-muted"></i>
            </span>
            @else
            <button class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 trigger-nama"
                data-id="{{ $ternak->id }}" data-nama="" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Tambah nama panggilan hewan">
                <i class="bi bi-plus"></i> Nama
            </button>
            @endif
        </div>
    </div>

    {{-- status badges --}}
    <div class="d-flex align-items-center gap-2">
        @php
            $latestPemeriksaan = $ternak->pemeriksaanSyariat->sortByDesc('tanggal_pemeriksaan')->first();
            $isLayak = $latestPemeriksaan && $latestPemeriksaan->status === 'layak_qurban';
            $hasLog = isset($ternak->log_kesehatans_count) ? ($ternak->log_kesehatans_count > 0) : $ternak->logKesehatans()->exists();
        @endphp

        {{-- kelayakan kurban --}}
        @if (!$latestPemeriksaan)
            <span class="badge bg-warning rounded-pill px-3 py-2">
                <span class="badge-text-full">Belum dicek</span>
                <span class="badge-text-compact">Belum Cek</span>
            </span>
        @elseif ($isLayak)
            <span class="badge bg-success rounded-pill px-3 py-2">
                <span class="badge-text-full">Layak Qurban</span>
                <span class="badge-text-compact">Layak</span>
            </span>
        @else
            <span class="badge bg-danger rounded-pill px-3 py-2">
                <span class="badge-text-full">Tidak Layak</span>
                <span class="badge-text-compact">T. Layak</span>
            </span>
        @endif

        {{-- status kesehatan --}}
        @if (!$hasLog)
            <span class="badge bg-warning rounded-pill px-3 py-2">
                <span class="badge-text-full">Belum diperiksa</span>
                <span class="badge-text-compact">Belum Cek</span>
            </span>
        @elseif ($ternak->is_karantina)
            <span class="badge bg-danger rounded-pill px-3 py-2">
                <span class="badge-text-full">Di Karantina</span>
                <span class="badge-text-compact">Karantina</span>
            </span>
        @else
            <span class="badge bg-success rounded-pill px-3 py-2">
                <span class="badge-text-full">Tersedia</span>
                <span class="badge-text-compact">Tersedia</span>
            </span>
        @endif

        {{-- skkh verifikasi --}}
        @if ($latestPemeriksaan && $latestPemeriksaan->dokumen_skkh_id)
            <span class="badge bg-info text-white rounded-pill px-2 py-1" 
                  style="cursor: pointer;"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi SKKH">
                <i class="bi bi-check-lg"></i>
            </span>
        @endif
    </div>
</div>

{{-- modal nama panggilan --}}
<div class="modal fade" id="modalNamaPanggilan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formNamaPanggilan">
                <div class="modal-header border-bottom-0 pb-0">
                    <h1 class="modal-title fs-6 fw-bold text-muted">Nama Panggilan Hewan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_nama_ternak_id">

                    <div class="mb-2">
                        <input type="text" class="form-control text-center fw-bold text-primary"
                            id="input_nama_panggilan" name="nama_panggilan" placeholder="Misal: Si Kliwon"
                            autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-danger d-none" id="btnHapusNama"
                        title="Hapus Nama">
                        <i class="bi bi-trash"></i>
                    </button>

                    <div class="ms-auto">
                        <button type="submit" class="btn btn-sm btn-primary px-3" id="btnSimpanNama">
                            <span class="spinner-border spinner-border-sm d-none" id="loadingNama" role="status"
                                aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // 1. Inisialisasi Tooltip Bootstrap 5
        // Ini wajib dipanggil agar title muncul sebagai tooltip hitam elegan, bukan bawaan browser
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
            tooltipTriggerEl));

        // 2. Logic Modal Nama Panggilan
        const containerTernak = document.getElementById('ternakContainer');
        const modalNamaEl = document.getElementById('modalNamaPanggilan');
        const modalNamaInstance = bootstrap.Modal.getOrCreateInstance(modalNamaEl);
        const modalTambahInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahTernak'));
        const formNama = document.getElementById('formNamaPanggilan');
        const inputIdTernak = document.getElementById('edit_nama_ternak_id');
        const inputNama = document.getElementById('input_nama_panggilan');
        const btnHapusNama = document.getElementById('btnHapusNama');
        const btnSimpanNama = document.getElementById('btnSimpanNama');
        const loadingNama = document.getElementById('loadingNama');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Event Delegation: Menangkap klik pada tombol/badge nama di dalam list ternak
        containerTernak.addEventListener('click', function (e) {
            let triggerEl = e.target.closest('.trigger-nama');
            if (triggerEl) {
                // Sembunyikan tooltip yang sedang aktif agar tidak "nyangkut" saat modal terbuka
                let tooltipInstance = bootstrap.Tooltip.getInstance(triggerEl);
                if (tooltipInstance) tooltipInstance.hide();

                let id = triggerEl.getAttribute('data-id');
                let nama = triggerEl.getAttribute('data-nama');

                // Isi form di modal
                inputIdTernak.value = id;
                inputNama.value = nama;

                // Tampilkan tombol tong sampah HANYA jika nama sudah ada
                if (nama.trim() !== '') {
                    btnHapusNama.classList.remove('d-none');
                } else {
                    btnHapusNama.classList.add('d-none');
                }

                modalNamaInstance.show();

                // Auto focus ke input saat modal terbuka
                modalNamaEl.addEventListener('shown.bs.modal', () => {
                    inputNama.focus();
                }, {
                    once: true
                });
            }
        });

        // Fungsi utama untuk submit AJAX (Update & Delete pakai fungsi ini)
        function submitNamaPanggilan(idTernak, namaBaru) {
            // UX Loading
            btnSimpanNama.disabled = true;
            btnHapusNama.disabled = true;
            loadingNama.classList.remove('d-none');

            let formData = new FormData();
            formData.append('nama_panggilan', namaBaru);
            formData.append('_method', 'PATCH'); // Spoofing PATCH method

            fetch(`/ternak/${idTernak}/nama-panggilan`, {
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
                        // Update UI di Card HTML secara dinamis
                        let containerHtml = document.getElementById(`nama-container-${idTernak}`);

                        if (data.data.nama_panggilan) {
                            // Jika ada namanya, render Badge
                            containerHtml.innerHTML = `
                        <span class="badge bg-light text-dark border rounded-pill trigger-nama" 
                              style="cursor: pointer; font-size: 0.85rem;"
                              data-id="${data.data.id}" 
                              data-nama="${data.data.nama_panggilan}"
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top"
                              title="Klik untuk ubah/hapus nama">
                            "${data.data.nama_panggilan}" <i class="bi bi-pencil-square ms-1 text-muted"></i>
                        </span>
                    `;
                        } else {
                            // Jika dikosongkan/dihapus, render tombol Add
                            containerHtml.innerHTML = `
                        <button class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 trigger-nama"
                                data-id="${data.data.id}" 
                                data-nama=""
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top"
                                title="Tambah nama panggilan hewan">
                            <i class="bi bi-plus"></i> Nama
                        </button>
                    `;
                        }

                        // Inisialisasi ulang tooltip untuk elemen yang baru dirender
                        let newTrigger = containerHtml.querySelector('.trigger-nama');
                        new bootstrap.Tooltip(newTrigger);

                        modalNamaInstance.hide();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan saat mengubah nama.');
                })
                .finally(() => {
                    btnSimpanNama.disabled = false;
                    btnHapusNama.disabled = false;
                    loadingNama.classList.add('d-none');
                });
        }

        // Submit dari tombol Simpan / Enter
        formNama.addEventListener('submit', function (e) {
            e.preventDefault();
            submitNamaPanggilan(inputIdTernak.value, inputNama.value);
        });

        // Submit khusus tombol hapus (mengirim string kosong)
        btnHapusNama.addEventListener('click', function () {
            submitNamaPanggilan(inputIdTernak.value, '');
        });

    });

</script>
@endpush
