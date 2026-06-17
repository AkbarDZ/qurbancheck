<div class="card shadow-sm border-1 mb-4" id="card-ternak-{{ $ternak->id }}">
    <div class="row g-0">
        <div class="col-md-8 col-lg-9">
            <div class="card-body d-flex flex-column h-100">

                @include('dashboards.ternak.components.modal-nama-panggilan')

                <div class="row text-dark mb-4">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Kategori Ternak</small>
                        <strong>{{ $ternak->ras->tipeTernak->nama_jenis }} |
                            {{ $ternak->ras->nama_ras }}</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Lokasi Kandang</small>
                        <strong>{{ $ternak->kandang->nama_kandang }}</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Jenis Kelamin</small>
                        <strong>{{ ucfirst($ternak->jenis_kelamin) }}</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Berat Terakhir</small>
                        <strong class="fs-5 text-success text-berat">
                            {{ $ternak->logBerats->first() ? $ternak->logBerats->first()->berat_kg . ' Kg' : 'Belum ditimbang' }}
                        </strong>
                    </div>
                </div>

                <div class="mt-auto d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm btn-edit-ternak"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}"
                        data-ras="{{ $ternak->ras_id }}" data-kandang="{{ $ternak->kandang_id }}"
                        data-gender="{{ $ternak->jenis_kelamin }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-outline-info btn-sm btn-perkembangan-berat"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}">
                        <i class="bi bi-bar-chart-line"></i> Perkembangan Berat
                    </button>
                    <button class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-heart-pulse"></i> Data Kesehatan
                    </button>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="bi bi-clipboard-pulse"></i> Kelayakan Kurban
                    </button>
                    <button class="btn btn-outline-danger btn-sm ms-auto btn-delete-ternak"
                        data-id="{{ $ternak->id }}">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>

            </div>
        </div>

        <div class="col-md-4 col-lg-3 bg-white">
            <img src="{{ $ternak->dir_foto_hewan ? asset('storage/'.$ternak->dir_foto_hewan) : asset('image/icons/placeholder.png') }}"
                class="img-fluid rounded-end h-100 w-100 object-fit-cover"
                alt="Foto Ternak {{ $ternak->nomor_eartag }}" style="min-height: 250px;">
        </div>
    </div>
</div>
