<div class="modal fade" id="modalDetailSKKH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-dark"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Detail Dokumen SKKH</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div id="loadingDetailSKKH" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Menarik data dokumen...</p>
                </div>

                <div id="kontenDetailSKKH">
                    <!-- Banner Info SKKH -->
                    <div class="card bg-light border-0 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <span class="text-muted small d-block mb-1">No. Surat</span>
                                    <strong class="text-dark fs-6" id="skkh_detail_no_surat">-</strong>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <span class="text-muted small d-block mb-1">Instansi Penerbit</span>
                                    <strong class="text-dark fs-6" id="skkh_detail_instansi">-</strong>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <span class="text-muted small d-block mb-1">Dokter Pemeriksa</span>
                                    <strong class="text-dark fs-6" id="skkh_detail_dokter">-</strong>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <span class="text-muted small d-block mb-1">Tanggal Terbit</span>
                                    <strong class="text-dark fs-6" id="skkh_detail_tgl_terbit">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linked Animals Table -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">
                            <i class="bi bi-cow me-1 text-primary"></i> Hewan yang Ditautkan (<span id="skkh_detail_count_ternak">0</span>)
                        </h6>
                    </div>
                    
                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Eartag</th>
                                    <th>Panggilan</th>
                                    <th>Tipe / Ras</th>
                                    <th>Kandang</th>
                                    <th class="text-center pe-4">Status Kelayakan</th>
                                </tr>
                            </thead>
                            <tbody id="skkh_detail_tbody_ternak">
                                <!-- Diisi dinamis via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light border-top-0 py-2">
                <a href="#" id="skkh_detail_download_btn" target="_blank" class="btn btn-outline-primary px-4">
                    <i class="bi bi-download me-1"></i> Unduh PDF
                </a>
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
