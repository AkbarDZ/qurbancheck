<tr>
    <td class="py-3 ps-4 fw-bold text-dark">{{ $doc->nomor_surat ?? '-' }}</td>
    <td class="py-3">{{ $doc->instansi_penerbit }}</td>
    <td class="py-3">{{ $doc->nama_dokter_pemeriksa }}</td>
    <td class="py-3">{{ \Carbon\Carbon::parse($doc->tanggal_terbit)->format('d M Y') }}</td>
    <td class="py-3 text-end pe-4">
        <button class="btn btn-sm btn-outline-secondary btn-detail-skkh me-1"
            data-id="{{ $doc->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail SKKH">
            <i class="bi bi-eye"></i>
        </button>
        <a href="{{ Storage::disk('s3')->url($doc->dir_bukti_skkh) }}" target="_blank"
            class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh PDF">
            <i class="bi bi-download"></i>
        </a>
        <button class="btn btn-sm btn-outline-danger btn-delete-skkh"
            data-id="{{ $doc->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Dokumen SKKH">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
