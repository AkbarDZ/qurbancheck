<div class="modal fade" id="modalDetailPemeriksaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h1 class="modal-title fs-5 fw-bold text-dark"><i class="bi bi-file-text me-2"></i> Laporan Cek Fisik Syariat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div id="loadingDetailSyariat" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Menarik data inspeksi...</p>
                </div>

                <div id="kontenDetailSyariat">
                    <div class="rounded p-3 mb-4 text-center border" id="detail_banner_status">
                        <h4 class="fw-bold mb-1" id="detail_teks_status">-</h4>
                        <small id="detail_sub_status">-</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted small fw-bold text-uppercase mb-2">Informasi Hewan</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted ps-0" width="100">No. Eartag</td>
                                    <td class="fw-bold text-primary fs-5" id="detail_eartag">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Panggilan</td>
                                    <td class="fw-semibold" id="detail_nama_panggilan">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small fw-bold text-uppercase mb-2">Informasi Inspeksi</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted ps-0" width="100">Tanggal</td>
                                    <td class="fw-semibold" id="detail_tanggal">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Petugas</td>
                                    <td class="fw-semibold" id="detail_petugas">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h6 class="text-muted small fw-bold text-uppercase mb-3 border-bottom pb-2">Rincian Hasil Checklist</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kriteria Syariat</th>
                                    <th class="text-center" width="120">Kondisi</th>
                                    <th>Catatan Temuan</th>
                                </tr>
                            </thead>
                            <tbody id="detail_tbody_kriteria">
                                </tbody>
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