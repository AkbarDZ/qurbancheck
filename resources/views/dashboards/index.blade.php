@extends('layouts.app')

@section('title', 'Dashboard - Sistem Qurban')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-dark">HOMEPAGE</h3>
        </div>
    
    <div class="card border bg-success mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="card shadow-sm border-info h-100">
                        <div class="card-body p-2 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-2 text-primary">
                                <i class="bi bi-droplet-half fs-5"></i>
                            </div>
                            <div>
                                <small class="card-title text-muted mb-0 d-block">Total Ternak</small>
                                <h6 class="mb-0 fw-bold">150</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card shadow-sm border-success h-100">
                        <div class="card-body p-2 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-2 text-success">
                                <i class="bi bi-check-circle fs-5"></i>
                            </div>
                            <div>
                                <small class="card-title text-muted mb-0 d-block">Layak Qurban</small>
                                <h6 class="mb-0 fw-bold">124</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card shadow-sm border-warning h-100">
                        <div class="card-body p-2 d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-2 text-warning">
                                <i class="bi bi-heart-pulse fs-5"></i>
                            </div>
                            <div>
                                <small class="card-title text-muted mb-0 d-block">Perawatan</small>
                                <h6 class="mb-0 fw-bold">12</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0">Pemeriksaan Terbaru</h6>
        </div>
        <div class="card-body">
            <p class="text-muted">Tabel data pemeriksaan akan diletakkan di sini.</p>
        </div>
    </div>
@endsection