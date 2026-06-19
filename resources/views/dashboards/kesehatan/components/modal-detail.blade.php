<div class="modal fade" id="modalDetailKesehatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-dark"><i class="bi bi-file-earmark-medical me-2"></i>Detail Rekam Medis</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div id="loadingDetail" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Mengambil data...</p>
                </div>

                <div id="kontenDetail">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Informasi Hewan</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded px-3 py-2 me-3">
                                    <i class="bi bi-tag-fill fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0" id="detail_eartag">-</h5>
                                    <small class="text-muted" id="detail_nama_hewan">-</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Detail Pemeriksaan</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted ps-0" width="120">Tanggal</td>
                                        <td class="fw-semibold" id="detail_tanggal">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Petugas</td>
                                        <td class="fw-semibold" id="detail_petugas">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="border-light">

                    <h6 class="text-muted small fw-bold text-uppercase mb-3 mt-4">Gejala Klinis & Diagnosa</h6>
                    <div class="bg-light rounded p-3 mb-4">
                        <p class="mb-0 text-dark" id="detail_gejala">-</p>
                    </div>

                    <div id="container_foto_gejala" class="mb-4 d-none">
                        <h6 class="text-muted small fw-bold text-uppercase mb-2">Lampiran Foto Gejala</h6>
                        <img src="" id="detail_foto" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: cover;" alt="Foto Gejala">
                    </div>

                    <hr class="border-light">

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Tindakan & Pengobatan</h6>
                        <span class="badge bg-danger rounded-pill d-none" id="detail_badge_karantina"><i class="bi bi-exclamation-triangle me-1"></i> Hewan Dikarantina</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Obat / Tindakan</th>
                                    <th>Dosis</th>
                                    <th>Catatan</th>
                                    <th class="text-end">Biaya</th>
                                </tr>
                            </thead>
                            <tbody id="detail_tbody_pengobatan">
                                </tbody>
                            <tfoot class="table-light d-none" id="tfoot_total_biaya">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Biaya:</td>
                                    <td class="text-end fw-bold text-primary" id="detail_total_biaya">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 py-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>