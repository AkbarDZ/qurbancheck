@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">

            <h2 class="fw-bold">
                Kesehatan Ternak Qurban
            </h2>

            <p class="text-muted">
                Kelola data kesehatan ternak qurban
            </p>

        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">

        <button class="btn btn-success">

            <i class="fas fa-plus"></i>
            Tambah Pemeriksaan

        </button>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <input type="text" class="form-control" placeholder="Cari Eartag">

                </div>

                <div class="col-md-4">

                    <select class="form-select">

                        <option selected disabled>
                            Pilih Status
                        </option>

                        <option value="Sehat">
                            Sehat
                        </option>

                        <option value="Sakit">
                            Sakit
                        </option>

                        <option value="Karantina">
                            Karantina
                        </option>

                    </select>

                </div>

                <div class="col-md-4">

                    <button class="btn btn-primary w-100">
                        Cari Data
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- Tabel -->
    <div class="card shadow border-0">

        <div class="card-header">

            <h5 class="mb-0">
                Daftar kesehatan ternak qurban
            </h5>

        </div>

        <div class="card-body">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Eartag</th>
                        <th>Nama Ternak</th>
                        <th>Status</th>
                        <th>Tanggal</th>
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
