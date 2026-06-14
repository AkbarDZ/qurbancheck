@extends('layouts.app')

@section('title', 'Master Data - Sistem Qurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-dark">Master Data</h3>
        <p class="text-muted mb-0">Kelola data referensi kandang, jenis ternak, dan kriteria qurban.</p>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
        <ul class="nav nav-tabs" id="masterDataTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-semibold" id="tipe-tab" data-bs-toggle="tab"
                    data-bs-target="#tipe-pane" type="button" role="tab">Tipe Ternak</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="ras-tab" data-bs-toggle="tab"
                    data-bs-target="#ras-pane" type="button" role="tab">Ras Ternak</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="kandang-tab" data-bs-toggle="tab"
                    data-bs-target="#kandang-pane" type="button" role="tab">Kandang</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="kriteria-tab" data-bs-toggle="tab"
                    data-bs-target="#kriteria-pane" type="button" role="tab">Kriteria Qurban</button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="masterDataTabContent">

            @include('dashboards.master.components.tab-tipe')
            @include('dashboards.master.components.tab-ras')
            @include('dashboards.master.components.tab-kandang')
            @include('dashboards.master.components.tab-kriteria')

        </div>
    </div>
</div>
@endsection
