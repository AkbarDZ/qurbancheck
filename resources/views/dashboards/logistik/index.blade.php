@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">

            <h2 class="fw-bold mb-1">
                Logistik Qurban
            </h2>

            <p class="text-muted mb-0">
                Kelola stok pakan dan distribusi ke kandang
            </p>

        </div>
    </div>

    <!-- Gudang Pakan -->
    <div class="card shadow border-0 mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-0">Gudang Pakan</h5>
                <small class="text-muted">
                    Kelola stok dan harga pakan
                </small>
            </div>

            <button class="btn btn-success">
                + Tambah Stok
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-6">

                    <input type="text" class="form-control" placeholder="Cari nama pakan...">

                </div>

            </div>

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Nama Pakan</th>
                        <th>Stok Saat Ini (Kg)</th>
                        <th>Harga / Kg</th>
                        <th>Total Nilai Stok</th>
                        <th>Tindakan</th>

                    </tr>

                </thead>

                <tbody>


                </tbody>

            </table>

        </div>

    </div>


    <div class="card shadow border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Distribusi Pakan
            </h5>

            <small class="text-muted">
                Catat pakan yang diberikan ke kandang
            </small>

        </div>

        <div class="card-body">

            <!-- Form Distribusi -->

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Tanggal Distribusi
                    </label>

                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Pilih Pakan
                    </label>

                    <select class="form-select">

                        <option selected disabled>
                            Pilih Pakan
                        </option>

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Pilih Kandang
                    </label>

                    <select class="form-select">

                        <option selected disabled>
                            Pilih Kandang
                        </option>

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Jumlah (Kg)
                    </label>

                    <input type="number" class="form-control" placeholder="0">

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Harga / Kg
                    </label>

                    <input type="text" class="form-control" readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Total Biaya
                    </label>

                    <input type="text" class="form-control" readonly>

                </div>

            </div>

            <button class="btn btn-primary mb-4">

                Simpan Distribusi

            </button>

            <hr>

            <h5 class="mb-3">
                Riwayat Distribusi
            </h5>

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Tanggal</th>
                        <th>Pakan</th>
                        <th>Kandang</th>
                        <th>Jumlah (Kg)</th>
                        <th>Total Biaya</th>
                        <th>Tindakan</th>

                    </tr>

                </thead>

                <tbody>


                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
