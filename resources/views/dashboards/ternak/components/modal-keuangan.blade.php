<!-- Modal Kartu Rapor Keuangan -->
<div class="modal fade" id="modalKeuangan" tabindex="-1" aria-labelledby="modalKeuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="background-color: #fdfdfd; border-radius: 8px;">
            <div class="modal-header border-bottom-0 pb-0 text-center justify-content-center position-relative">
                <h5 class="modal-title w-100 text-center fw-bold" id="modalKeuanganLabel" style="font-family: monospace; letter-spacing: 1px;">KARTU RAPOR KEUANGAN</h5>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-3" style="font-family: monospace; color: #333;">
                <div id="keuanganLoading" class="text-center py-4">
                    <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                    <div class="mt-2" style="font-size: 0.85rem;">Menghitung data...</div>
                </div>
                
                <div id="keuanganContent" class="d-none">
                    <div class="text-center mb-3">
                        <small class="text-muted d-block" style="font-family: 'Inter', sans-serif;">Ternak Eartag</small>
                        <strong id="kEartag" class="fs-5">-</strong>
                    </div>
                    
                    <div class="border-top border-bottom py-2 mb-3" style="border-style: dashed !important; border-color: #aaa !important; border-left: none; border-right: none;">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.9rem;">
                            <span>Modal Awal:</span>
                            <span id="kModalAwal" class="text-end">Rp 0</span>
                        </div>
                        
                        <div class="mt-2 mb-1">
                            <span class="d-block text-muted text-center" style="font-size: 0.75rem; border-bottom: 1px dashed #ddd; margin-bottom: 4px;">Rincian Berjalan</span>
                            <div id="kRincianContainer" style="max-height: 120px; overflow-y: auto; overflow-x: hidden; font-size: 0.8rem;">
                                <!-- Rincian bulanan akan dirender di sini via JS -->
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 pt-1 border-top" style="font-size: 0.85rem; border-top-style: dashed !important; border-color: #ddd !important;">
                            <span class="text-truncate me-2 text-muted" title="Total Proporsional Kandang">Total Pakan:</span>
                            <span id="kBiayaPakan" class="text-end text-muted">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem;">
                            <span class="text-muted">Total Medis:</span>
                            <span id="kBiayaMedis" class="text-end text-muted">Rp 0</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between fw-bold fs-6 mb-3">
                        <span>TOTAL RIIL:</span>
                        <span id="kTotalRiil" class="text-end">Rp 0</span>
                    </div>

                    <div class="alert alert-warning p-2 text-center mb-0" style="font-family: 'Inter', sans-serif; font-size: 0.8rem; border-radius: 6px;">
                        💡 <strong>Saran Sistem:</strong><br>
                        Jual di atas <strong id="kSaranJual" class="text-danger">Rp 0</strong> untuk menghindari kerugian.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
