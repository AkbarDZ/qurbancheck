<div class="modal fade" id="modalPerkembanganBerat" tabindex="-1" aria-hidden="true">
    <style>
        #formTambahBerat .invalid-feedback:empty {
            display: none !important;
        }
    </style>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Perkembangan Berat <span id="label_eartag_berat" class="text-primary"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                
                <div class="alert alert-danger d-none py-2 small shadow-sm mb-3" id="berat_global_error">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="berat_global_error_msg"></span>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3">
             
                        <form id="formTambahBerat">
                            <input type="hidden" id="berat_ternak_id">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted mb-1">Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" name="tanggal_timbang" id="input_tanggal_timbang" max="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_tanggal_timbang" style="font-size: 0.75rem;"></div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted mb-1">Berat (Kg)</label>
                                    <input type="number" min="1" step="0.01" class="form-control form-control-sm" name="berat_kg" id="input_berat_kg" required>
                                    <div class="invalid-feedback d-block mt-1 fw-semibold text-danger" id="error_berat_kg" style="font-size: 0.75rem;"></div>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="submit" class="btn btn-sm btn-primary" id="btnSimpanBerat"><i class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-hover table-borderless table-striped mb-0 text-center">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="py-3 text-muted small fw-bold text-uppercase">Tanggal Timbang</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase">Berat (Kg)</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyBerat">
                                    <tr><td colspan="2" class="py-4 text-muted"><span class="spinner-border spinner-border-sm" role="status"></span> Memuat data...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="paginationBerat" class="d-flex justify-content-center py-2 bg-light border-top"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
