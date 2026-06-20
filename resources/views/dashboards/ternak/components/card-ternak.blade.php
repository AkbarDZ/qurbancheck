<div class="card shadow-sm border-1 mb-4" id="card-ternak-{{ $ternak->id }}" style="overflow: hidden;">
    <div class="row g-0">
        <div class="col-md-8 col-lg-8">
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
                        <strong class="{{ ($ternak->kandang->ternaks_count ?? 0) >= ($ternak->kandang->kapasitas_maksimal ?? 0) ? 'text-danger' : '' }}">{{ $ternak->kandang->nama_kandang }}</strong>
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
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Asal Usul</small>
                        <strong>{{ $ternak->harga_beli_awal > 0 ? 'Pembelian' : 'Lahir di Peternakan' }}</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        @if($ternak->harga_beli_awal > 0)
                            <small class="text-muted d-block text-uppercase fw-bold"
                                style="font-size: 0.7rem;">Harga Beli</small>
                            <strong class="text-primary">Rp {{ number_format($ternak->harga_beli_awal, 0, ',', '.') }}</strong>
                        @else
                            <small class="text-muted d-block text-uppercase fw-bold"
                                style="font-size: 0.7rem;">Tanggal Lahir</small>
                            <strong>{{ $ternak->tanggal_lahir ? $ternak->tanggal_lahir->format('d M Y') : '-' }}</strong>
                        @endif
                    </div>
                </div>

                <div class="mt-auto d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm btn-edit-ternak"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}"
                        data-ras="{{ $ternak->ras_id }}" data-kandang="{{ $ternak->kandang_id }}"
                        data-gender="{{ $ternak->jenis_kelamin }}"
                        data-foto="{{ $ternak->dir_foto_hewan ? asset('storage/'.$ternak->dir_foto_hewan) : '' }}"
                        data-harga-beli="{{ $ternak->harga_beli_awal }}"
                        data-tanggal-lahir="{{ $ternak->tanggal_lahir ? $ternak->tanggal_lahir->format('Y-m-d') : '' }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-outline-info btn-sm btn-perkembangan-berat"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}">
                        <i class="bi bi-bar-chart-line"></i> Perkembangan Berat
                    </button>
                    <a href="{{ url('/kesehatan?tambah_ternak_id=' . $ternak->id) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-heart-pulse"></i> Data Kesehatan
                    </a>
                    @php
                        $latestPemeriksaan = $ternak->pemeriksaanSyariat->sortByDesc('id')->first();
                    @endphp
                    @if ($latestPemeriksaan)
                        <a href="{{ url('/syariat?show_pemeriksaan_id=' . $latestPemeriksaan->id) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-clipboard-pulse"></i> Kelayakan Kurban
                        </a>
                    @else
                        <a href="{{ url('/syariat?tambah_ternak_id=' . $ternak->id) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-clipboard-pulse"></i> Kelayakan Kurban
                        </a>
                    @endif
                    <button class="btn btn-outline-primary btn-sm btn-keuangan-ternak"
                        data-id="{{ $ternak->id }}" data-bs-toggle="tooltip" data-bs-title="Kartu Rapor Keuangan">
                        <i class="bi bi-receipt"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm ms-auto btn-delete-ternak"
                        data-id="{{ $ternak->id }}">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>

            </div>
        </div>

        @if($ternak->dir_foto_hewan)
            <div class="col-md-4 col-lg-4 p-0">
                <img src="{{ asset('storage/'.$ternak->dir_foto_hewan) }}"
                    class="img-fluid h-100 w-100 object-fit-cover"
                    alt="Foto Ternak {{ $ternak->nomor_eartag }}" style="min-height: 250px;">
            </div>
        @else
            <div class="col-md-4 col-lg-4 p-0">
                <img src="{{ asset('image/icons/placeholder.png') }}"
                    class="img-fluid h-100 w-100 object-fit-cover"
                    alt="Foto Ternak {{ $ternak->nomor_eartag }}" style="min-height: 250px;">
            </div>
        @endif
    </div>
</div>
