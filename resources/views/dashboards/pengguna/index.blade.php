@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Sistem Qurban')

@section('content')
<div class="card shadow border-1 mb-4">
    <div class="card-body">
        <h3 class="fw-bold mb-0 text-dark">Manajemen Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun pengguna sistem, perbarui hak akses dan data login.</p>
    </div>
</div>

<div class="card shadow border-1">
    <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Pengguna</h5>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive rounded-3 border border-light-subtle shadow-sm bg-white mx-4 mb-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr class="border-bottom border-light-subtle">
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem; width: 60px;">No</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-person-fill me-2 text-muted"></i>Nama</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-envelope-fill me-2 text-muted"></i>Email</th>
                        <th class="py-3 px-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="bi bi-shield-lock-fill me-2 text-muted"></i>Role</th>
                        <th class="py-3 px-3 text-muted fw-bold text-end" style="font-size: 0.85rem; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBodyPengguna">
                    @forelse($users as $index => $user)
                    <tr id="row-pengguna-{{ $user->id }}">
                        <td class="py-3 px-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                        <td class="py-3 px-3 col-name fw-bold text-dark">{{ $user->name }}</td>
                        <td class="py-3 px-3 col-email fw-semibold text-secondary">{{ $user->email }}</td>
                        <td class="py-3 px-3 col-role">
                            @if($user->role === 'owner/admin')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-shield-fill-check me-1"></i> Owner/Admin
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-person me-1"></i> Pekerja
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit-pengguna" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pengguna">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-pengguna" data-id="{{ $user->id }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Pengguna">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-3 text-muted opacity-50"></i>
                            <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Pengguna</h6>
                            <p class="small text-muted mb-0">Klik tombol "Tambah Pengguna" untuk mendaftarkan user baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination Container -->
        <div id="paginationPengguna" class="d-flex justify-content-center mt-2 pb-4"></div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahPengguna" tabindex="-1" aria-labelledby="modalTambahPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahPengguna">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPenggunaLabel"><i class="bi bi-person-plus-fill me-2 text-success"></i>Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                        <div class="invalid-feedback" id="error_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@qurban.com" required>
                        <div class="invalid-feedback" id="error_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role Akses</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="owner/admin">Owner/Admin</option>
                            <option value="pekerja">Pekerja</option>
                        </select>
                        <div class="invalid-feedback" id="error_role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        <div class="invalid-feedback" id="error_password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanPengguna">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingTambah" role="status" aria-hidden="true"></span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditPengguna" tabindex="-1" aria-labelledby="modalEditPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditPengguna">
                <input type="hidden" id="edit_id_pengguna">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPenggunaLabel"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback" id="error_edit_name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback" id="error_edit_email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role Akses</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="owner/admin">Owner/Admin</option>
                            <option value="pekerja">Pekerja</option>
                        </select>
                        <div class="invalid-feedback" id="error_edit_role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru <span class="text-muted small">(Opsional)</span></label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Biarkan kosong jika tidak ingin diubah">
                        <div class="invalid-feedback" id="error_edit_password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdatePengguna">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingEdit" role="status" aria-hidden="true"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@include('dashboards.pengguna.components.script')
