<div class="modal fade" id="modalTambahPemeriksaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" action="{{ url('/syariat/pemeriksaan') }}"
            enctype="multipart/form-data" id="formTambahPemeriksaan" method="POST">
            @csrf
            <div class="modal-header bg-light border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-dark"><i class="bi bi-clipboard2-check me-2"></i> Form
                    Pemeriksaan Syariat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light p-4">

                <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="error_global_pemeriksaan">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="error_msg_pemeriksaan"></span>
                </div>

                <!-- Informasi Hewan Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-cow text-primary me-2"></i> Informasi Pemeriksaan & Hewan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Left: Pilih Hewan -->
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-secondary mb-2">Pilih Hewan yang Diperiksa <span class="text-danger">*</span></label>
                                
                                <!-- Dynamic Options Collector -->
                                @php
                                    $filterTipes = [];
                                    $filterKandangs = [];
                                    foreach ($ternakBelumDiperiksa as $t) {
                                        if ($t->ras && $t->ras->tipeTernak) {
                                            $filterTipes[$t->ras->tipeTernak->id] = $t->ras->tipeTernak->nama_jenis;
                                        }
                                        if ($t->kandang) {
                                            $filterKandangs[$t->kandang->id] = $t->kandang->nama_kandang;
                                        }
                                    }
                                @endphp

                                <!-- Filters -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <select class="form-select form-select-sm border-light-subtle shadow-sm bg-light bg-opacity-50" id="filterTipeTernak">
                                            <option value="">Semua Tipe</option>
                                            @foreach($filterTipes as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select form-select-sm border-light-subtle shadow-sm bg-light bg-opacity-50" id="filterKandang">
                                            <option value="">Semua Kandang</option>
                                            @foreach($filterKandangs as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Checklist Area -->
                                <div class="border border-light-subtle rounded-3 bg-white p-2 shadow-inner" style="max-height: 180px; overflow-y: auto;">
                                    <div class="form-check border-bottom pb-2 mb-2 bg-light bg-opacity-70 p-2 rounded-2">
                                        <input class="form-check-input ms-1 text-primary" type="checkbox" id="checkAllTernak" style="cursor: pointer;">
                                        <label class="form-check-label fw-bold text-dark ms-2" for="checkAllTernak" style="cursor: pointer; font-size: 0.9rem;">
                                            Pilih Semua Hewan Terfilter
                                        </label>
                                    </div>

                                    <div id="containerCheckboxTernak">
                                        @forelse($ternakBelumDiperiksa as $ternak)
                                        @php
                                            $minUmur = $ternak->ras->tipeTernak->umur_minimal_qurban ?? 0;
                                            $isUnderAge = $ternak->umur_bulan < $minUmur;
                                            $isDisabled = $ternak->is_karantina || $isUnderAge;
                                        @endphp
                                        <div class="form-check mb-1 ps-4 py-1.5 hover-bg-light rounded-2 ternak-row"
                                             data-tipe-id="{{ $ternak->ras->tipeTernak->id ?? '' }}"
                                             data-kandang-id="{{ $ternak->kandang->id ?? '' }}"
                                             style="transition: background 0.15s ease-in-out;">
                                            <input class="form-check-input ternak-checkbox text-primary" type="checkbox"
                                                name="ternak_id[]" value="{{ $ternak->id }}"
                                                id="ternak_{{ $ternak->id }}"
                                                @if($isDisabled) disabled @endif
                                                style="cursor: @if($isDisabled) not-allowed @else pointer @endif;">
                                            <label class="form-check-label ms-2 d-flex align-items-center flex-wrap" for="ternak_{{ $ternak->id }}"
                                                style="cursor: @if($isDisabled) not-allowed @else pointer @endif; @if($isDisabled) opacity: 0.55; @endif">
                                                <span class="fw-bold text-primary me-2">{{ $ternak->nomor_eartag }}</span>
                                                <span class="text-secondary small">{{ $ternak->nama_panggilan ? '('.$ternak->nama_panggilan.')' : '' }}</span>
                                                
                                                @if($ternak->is_karantina)
                                                <span class="badge bg-danger-subtle text-danger border border-danger ms-2 py-1 px-2 rounded-pill fw-semibold" style="font-size: 0.65rem;">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Karantina
                                                </span>
                                                @endif

                                                @if($isUnderAge)
                                                <span class="badge bg-warning-subtle text-warning border border-warning ms-2 py-1 px-2 rounded-pill fw-semibold" style="font-size: 0.65rem;">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Cukup Umur ({{ $ternak->umur_bulan }}/{{ $minUmur }} bln)
                                                </span>
                                                @endif
                                            </label>
                                        </div>
                                        @empty
                                        <div class="text-center small text-muted py-4">
                                            <i class="bi bi-check2-circle fs-3 text-success d-block mb-1"></i>
                                            Semua hewan sudah diperiksa.
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_ternak_id" style="font-size: 0.75rem;">
                                    @error('ternak_id') {{ $message }} @enderror
                                </div>

                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark rounded-3 p-3 mt-3 d-flex gap-2 align-items-start mb-0" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle-fill text-warning fs-6"></i>
                                    <div>
                                        <strong>Tips:</strong> Centang banyak hewan sekaligus <b>hanya jika</b> mereka memiliki kondisi fisik yang persis sama (misalnya, semua dalam kondisi sehat tanpa cacat).
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Tanggal Pemeriksaan -->
                            <div class="col-md-5 d-flex flex-column justify-content-between">
                                <div>
                                    <label class="form-label small fw-bold text-secondary mb-2">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" class="form-control border-start-0 ps-0 fw-semibold text-dark" name="tanggal_pemeriksaan" id="tambah_tanggal_pemeriksaan"
                                            value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tanggal_pemeriksaan" style="font-size: 0.75rem;">
                                        @error('tanggal_pemeriksaan') {{ $message }} @enderror
                                    </div>
                                </div>
                                
                                {{-- <div class="card bg-light border-0 rounded-3 p-3 mt-3 d-none d-md-block">
                                    <h6 class="fw-bold text-dark mb-1 small"><i class="bi bi-shield-check text-success me-1"></i> Standar Kelayakan</h6>
                                    <p class="text-muted mb-0 small" style="font-size: 0.75rem; line-height: 1.4;">
                                        Pemeriksaan fisik syariat meliputi keutuhan organ tubuh (gigi tanggal/musinnah, tanduk, mata, telinga, kaki, dll.) sesuai syariat Islam.
                                    </p>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div
                            class="bg-primary bg-opacity-10 px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0">Checklist Kondisi Fisik</h6>
                            <small class="text-muted">Matikan saklar jika terdapat cacat</small>
                        </div>

                        <div class="list-group list-group-flush">
                            @forelse($kriterias as $kriteria)
                            <div class="list-group-item px-4 py-3 bg-white">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">
                                            {{ $kriteria->nama_kriteria }}
                                            @if($kriteria->is_fatal)
                                            <span class="badge bg-danger ms-2" style="font-size: 0.65rem;">Cacat Fatal
                                                (Tidak Sah)</span>
                                            @else
                                            <span class="badge bg-warning text-dark ms-2"
                                                style="font-size: 0.65rem;">Cacat Ringan (Sah)</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $kriteria->deskripsi }}</small>
                                    </div>

                                    <div class="form-check form-switch ms-3 mt-1">
                                        <input class="form-check-input switch-kriteria" type="checkbox" role="switch"
                                            name="kriteria[{{ $kriteria->id }}][is_lolos]"
                                            id="switch_{{ $kriteria->id }}" value="1" checked
                                            style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                        <label class="form-check-label ms-2 fw-bold text-success label-switch"
                                            for="switch_{{ $kriteria->id }}" style="cursor: pointer;">Aman</label>
                                    </div>
                                </div>

                                <div class="mt-3 container-catatan d-none bg-light p-3 rounded border border-danger border-opacity-25"
                                    id="catatan_{{ $kriteria->id }}">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-danger">Keterangan Cacat <span
                                                class="text-dark">*</span></label>
                                        <input type="text" class="form-control form-control-sm border-danger"
                                            name="kriteria[{{ $kriteria->id }}][catatan]"
                                            placeholder="Misal: Mata kanan berlendir dan putih...">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-dark">Foto Bukti Cacat <span
                                                class="text-secondary fw-normal">(Opsional)</span></label>
                                        <input type="file" class="form-control form-control-sm"
                                            name="kriteria[{{ $kriteria->id }}][foto_cacat]" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-4 text-center text-muted">
                                Master data kriteria belum diisi. Silakan jalankan Seeder KriteriaKurban.
                            </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer bg-white border-top py-2">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-4" id="btnSimpanPemeriksaan">Proses
                    Kelayakan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // LOGIKA PILIH SEMUA HEWAN (TERFILTER & DILUAR KARANTINA)
        const checkAll = document.getElementById('checkAllTernak');
        const filterTipe = document.getElementById('filterTipeTernak');
        const filterKandang = document.getElementById('filterKandang');

        // Initialize Select2 when modal is shown
        $('#modalTambahPemeriksaan').on('shown.bs.modal', function () {
            $('#filterTipeTernak').select2({
                dropdownParent: $('#modalTambahPemeriksaan'),
                width: '100%'
            });
            $('#filterKandang').select2({
                dropdownParent: $('#modalTambahPemeriksaan'),
                width: '100%'
            });
        });

        function applyFilters() {
            const tipeVal = filterTipe ? filterTipe.value : '';
            const kandangVal = filterKandang ? filterKandang.value : '';
            const rows = document.querySelectorAll('.ternak-row');

            rows.forEach(row => {
                const rowTipe = row.getAttribute('data-tipe-id');
                const rowKandang = row.getAttribute('data-kandang-id');

                const matchTipe = !tipeVal || rowTipe === tipeVal;
                const matchKandang = !kandangVal || rowKandang === kandangVal;

                if (matchTipe && matchKandang) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck if hidden
                    const cb = row.querySelector('.ternak-checkbox');
                    if (cb) {
                        cb.checked = false;
                    }
                }
            });

            updateCheckAllState();
        }

        // Bind Select2 change event using jQuery
        $('#filterTipeTernak, #filterKandang').on('change', applyFilters);

        function updateCheckAllState() {
            if (!checkAll) return;
            const visibleCheckboxes = Array.from(document.querySelectorAll('.ternak-row'))
                .filter(row => row.style.display !== 'none')
                .map(row => row.querySelector('.ternak-checkbox'))
                .filter(cb => cb && !cb.disabled);

            if (visibleCheckboxes.length === 0) {
                checkAll.checked = false;
                return;
            }

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            checkAll.checked = allChecked;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                const rows = document.querySelectorAll('.ternak-row');
                rows.forEach(row => {
                    const cb = row.querySelector('.ternak-checkbox');
                    if (cb && !cb.disabled && row.style.display !== 'none') {
                        cb.checked = checkAll.checked;
                    }
                });
            });
        }

        // Jika salah satu checkbox hewan di-check/uncheck, update state centang "Pilih Semua"
        const ternakCheckboxes = document.querySelectorAll('.ternak-checkbox');
        ternakCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateCheckAllState);
        });

        // 1. LOGIKA UI: MENGUBAH WARNA TOGGLE DAN MEMUNCULKAN CATATAN
        const switches = document.querySelectorAll('.switch-kriteria');
        switches.forEach(toggle => {
            toggle.addEventListener('change', function () {
                let label = this.nextElementSibling;
                let catatanContainer = this.closest('.list-group-item').querySelector(
                    '.container-catatan');

                if (this.checked) {
                    // Kondisi Lolos / Aman
                    label.innerText = 'Aman';
                    label.classList.remove('text-danger');
                    label.classList.add('text-success');
                    catatanContainer.classList.add('d-none');
                    catatanContainer.querySelector('input').value = ''; // Reset isi catatan
                } else {
                    // Kondisi Cacat
                    label.innerText = 'Cacat';
                    label.classList.remove('text-success');
                    label.classList.add('text-danger');
                    catatanContainer.classList.remove('d-none');
                    catatanContainer.querySelector('input').focus(); // Otomatis fokus ke input
                }
            });
        });

        // 2. Form submits normally via standard HTTP POST, JS AJAX submission removed.
    });
</script>
@endpush
