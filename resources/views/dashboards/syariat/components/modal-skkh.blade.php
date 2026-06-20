<div class="modal fade" id="modalUploadSKKH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" id="formUploadSKKH" action="{{ url('/syariat/skkh') }}"
            method="POST" enctype="multipart/form-data">
            <div class="modal-header bg-success bg-opacity-10 border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-success"><i class="bi bi-cloud-arrow-up me-2"></i> Upload Arsip
                    SKKH Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light p-4">

                <div class="alert alert-danger d-none py-2 small shadow-sm mb-4" id="error_global_skkh">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="error_msg_skkh"></span>
                </div>

                <div class="row">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Informasi Dokumen</h6>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Nomor Surat SKKH <span
                                            class="fw-normal text-secondary">(Opsional)</span></label>
                                    <input type="text" class="form-control" name="nomor_surat"
                                        placeholder="Misal: 524/123/DISNAK">
                                    <div class="invalid-feedback" id="error_nomor_surat"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Instansi Penerbit <span
                                            class="fw-normal text-secondary">(Opsional)</span></label>
                                    <input type="text" class="form-control" name="instansi_penerbit"
                                        placeholder="Misal: Dinas Peternakan Kab. Sleman">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Nama Dokter Pemeriksa <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_dokter_pemeriksa"
                                        placeholder="Drh. Budi Santoso" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tanggal Terbit <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_terbit"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">File Scan Dokumen (PDF/JPG/PNG)
                                        <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file_skkh" accept=".pdf,image/*"
                                        required>
                                    <div class="invalid-feedback" id="error_file_skkh"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <h6 class="fw-bold text-dark mb-0">Tautkan ke Hewan</h6>
                                    <span class="badge bg-primary rounded-pill">{{ count($pemeriksaanTanpaSkkh) }}
                                        Tersedia</span>
                                </div>
                                <p class="text-muted small mb-2">Pilih hewan yang terdaftar dalam SKKH ini. Hanya hewan
                                    berstatus <b>Layak Qurban</b> dan belum punya SKKH yang ditampilkan di sini.</p>

                                <!-- Dynamic Options Collector -->
                                @php
                                    $skkhFilterTipes = [];
                                    $skkhFilterKandangs = [];
                                    foreach ($pemeriksaanTanpaSkkh as $pem) {
                                        $t = $pem->ternak;
                                        if ($t) {
                                            if ($t->ras && $t->ras->tipeTernak) {
                                                $skkhFilterTipes[$t->ras->tipeTernak->id] = $t->ras->tipeTernak->nama_jenis;
                                            }
                                            if ($t->kandang) {
                                                $skkhFilterKandangs[$t->kandang->id] = $t->kandang->nama_kandang;
                                            }
                                        }
                                    }
                                @endphp

                                <!-- Filters -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <select class="form-select form-select-sm border-light-subtle shadow-sm bg-light bg-opacity-50" id="filterTipeSkkh">
                                            <option value="">Semua Tipe</option>
                                            @foreach($skkhFilterTipes as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select form-select-sm border-light-subtle shadow-sm bg-light bg-opacity-50" id="filterKandangSkkh">
                                            <option value="">Semua Kandang</option>
                                            @foreach($skkhFilterKandangs as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="border border-light-subtle rounded-3 bg-white p-2 flex-grow-1"
                                    style="max-height: 350px; overflow-y: auto;">
                                    <div class="form-check border-bottom pb-2 mb-2 bg-light p-2 rounded position-sticky top-0 shadow-sm"
                                        style="z-index: 10;">
                                        <input class="form-check-input ms-1 text-success" type="checkbox" id="checkAllSkkh" style="cursor: pointer;">
                                        <label class="form-check-label fw-bold text-dark ms-2" for="checkAllSkkh" style="cursor: pointer; font-size: 0.9rem;">
                                            Pilih Semua Hewan Terfilter
                                        </label>
                                    </div>

                                    <div id="containerCheckboxSkkh">
                                        @forelse($pemeriksaanTanpaSkkh as $pem)
                                        <div class="form-check mb-1 ps-4 py-1.5 hover-bg-light rounded-2 skkh-row"
                                             data-tipe-id="{{ $pem->ternak->ras->tipeTernak->id ?? '' }}"
                                             data-kandang-id="{{ $pem->ternak->kandang->id ?? '' }}"
                                             style="transition: background 0.15s ease-in-out;">
                                            <input class="form-check-input skkh-checkbox text-success" type="checkbox"
                                                name="pemeriksaan_ids[]" value="{{ $pem->id }}" id="pem_{{ $pem->id }}" style="cursor: pointer;">
                                            <label
                                                class="form-check-label ms-2 w-100 d-flex justify-content-between pe-3"
                                                for="pem_{{ $pem->id }}" style="cursor: pointer;">
                                                <span>
                                                    <span
                                                        class="fw-bold text-primary">{{ $pem->ternak->nomor_eartag }}</span>
                                                    {{ $pem->ternak->nama_panggilan ? '— '.$pem->ternak->nama_panggilan : '' }}
                                                </span>
                                                <small class="text-muted">Diperiksa:
                                                    {{ \Carbon\Carbon::parse($pem->tanggal_pemeriksaan)->format('d M') }}</small>
                                            </label>
                                        </div>
                                        @empty
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-emoji-frown fs-3 d-block mb-2 text-black-50"></i>
                                            Tidak ada hewan yang siap ditautkan.<br><small>Pastikan Anda sudah melakukan
                                                <b>Pemeriksaan Fisik</b> terlebih dahulu.</small>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block mt-2 fw-semibold text-danger"
                                    id="error_pemeriksaan_ids" style="font-size: 0.8rem;"></div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-white border-top py-2 d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-shield-check text-success"></i> Data dienkripsi &
                    diamankan.</small>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-4" id="btnSimpanSKKH"
                        {{ count($pemeriksaanTanpaSkkh) == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cloud-arrow-up me-1"></i> Upload & Tautkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
