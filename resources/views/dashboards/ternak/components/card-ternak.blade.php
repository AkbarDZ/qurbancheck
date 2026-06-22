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
                        <small class="text-muted d-block text-uppercase fw-bold"
                            style="font-size: 0.7rem;">Usia</small>
                        <strong>{{ $ternak->umur_bulan }} Bulan</strong>
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

                <div class="mt-auto d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-secondary btn-sm btn-edit-ternak"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}"
                        data-ras="{{ $ternak->ras_id }}" data-kandang="{{ $ternak->kandang_id }}"
                        data-gender="{{ $ternak->jenis_kelamin }}"
                        data-foto="{{ $ternak->dir_foto_hewan ? Storage::disk('s3')->url($ternak->dir_foto_hewan) : '' }}"
                        data-harga-beli="{{ $ternak->harga_beli_awal }}"
                        data-tanggal-lahir="{{ $ternak->tanggal_lahir ? $ternak->tanggal_lahir->format('Y-m-d') : '' }}"
                        data-umur-bulan="{{ $ternak->umur_bulan }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Ternak">
                        <i class="bi bi-pencil"></i><span class="btn-text-responsive ms-1">Edit</span>
                    </button>
                    <button class="btn btn-outline-info btn-sm btn-perkembangan-berat"
                        data-id="{{ $ternak->id }}" data-eartag="{{ $ternak->nomor_eartag }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Perkembangan Berat">
                        <i class="bi bi-bar-chart-line"></i><span class="btn-text-responsive ms-1">Perkembangan Berat</span>
                    </button>
                    <a href="{{ url('/kesehatan?tambah_ternak_id=' . $ternak->id) }}" class="btn btn-outline-warning btn-sm"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Data Kesehatan">
                        <i class="bi bi-heart-pulse"></i><span class="btn-text-responsive ms-1">Data Kesehatan</span>
                    </a>
                    @php
                        $latestPemeriksaan = $ternak->pemeriksaanSyariat->sortByDesc('id')->first();
                    @endphp
                    @if ($latestPemeriksaan)
                        <a href="{{ url('/syariat?show_pemeriksaan_id=' . $latestPemeriksaan->id) }}" class="btn btn-outline-success btn-sm"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Kelayakan Kurban">
                            <i class="bi bi-clipboard-pulse"></i><span class="btn-text-responsive ms-1">Kelayakan Kurban</span>
                        </a>
                    @else
                        <a href="{{ url('/syariat?tambah_ternak_id=' . $ternak->id) }}" class="btn btn-outline-success btn-sm"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Kelayakan Kurban">
                            <i class="bi bi-clipboard-pulse"></i><span class="btn-text-responsive ms-1">Kelayakan Kurban</span>
                        </a>
                    @endif
                    @if(Auth::user()->role === 'owner/admin')
                    <button class="btn btn-outline-primary btn-sm btn-keuangan-ternak"
                        data-id="{{ $ternak->id }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kartu Rapor Keuangan">
                        <i class="bi bi-receipt"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm ms-auto btn-delete-ternak"
                        data-id="{{ $ternak->id }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Ternak">
                        <i class="bi bi-trash"></i><span class="btn-text-responsive ms-1">Hapus</span>
                    </button>
                    @endif
                </div>

            </div>
        </div>

        @if($ternak->dir_foto_hewan)
            <div class="col-md-4 col-lg-4 p-0">
                <img src="{{ Storage::disk('s3')->url($ternak->dir_foto_hewan) }}"
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
