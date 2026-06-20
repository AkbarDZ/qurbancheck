<tr>
    <td class="py-3 ps-4">
        {{ \Carbon\Carbon::parse($cek->tanggal_pemeriksaan)->format('d M Y') }}
    </td>
    <td class="py-3 fw-bold text-primary">{{ $cek->ternak->nomor_eartag }}</td>
    <td class="py-3">{{ $cek->penanggungJawab->name ?? 'Admin (Belum Login)' }}</td>
    <td class="py-3">
        @if($cek->status == 'layak_qurban')
        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Sah / Layak Qurban</span>
        @else
        <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Cacat / Tidak Layak</span>
        @endif
    </td>
    <td class="py-3">
        @if($cek->dokumen_skkh_id)
        <span class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> Terverifikasi SKKH</span>
        @else
        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">Belum ada SKKH</span>
        @endif
    </td>
    <td class="py-3 text-end pe-4">
        <button class="btn btn-sm btn-outline-secondary me-1 btn-detail-pemeriksaan"
            data-id="{{ $cek->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
            <i class="bi bi-eye"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger btn-delete-pemeriksaan"
            data-id="{{ $cek->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Batalkan & Hapus Data">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
