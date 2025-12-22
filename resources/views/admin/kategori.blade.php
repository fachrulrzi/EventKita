@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Daftar Kategori Event')

@section('content')
<style>
    /* Custom Styling untuk Halaman Kategori */
    .category-avatar {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .admin-data-card {
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: white;
    }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        padding: 15px;
        border: none;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .slug-badge {
        background-color: #f1f5f9;
        color: #475569;
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .modal-content {
        border-radius: 1.5rem;
        border: none;
    }
    .form-control, .form-select {
        border-radius: 0.75rem;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    .form-control:focus {
        background-color: #fff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.2s;
    }
</style>

<div class="container-fluid pb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Kategori</h4>
            <p class="text-muted small mb-0">Kelola kategori event untuk mempermudah pencarian user.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 1rem;">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 1rem;">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div class="fw-bold">Terjadi Kesalahan:</div>
            </div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card admin-data-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="80">#</th>
                            <th>Info Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $placeholderIcon = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64'><rect width='100%' height='100%' fill='%23e9ecef'/><text x='50%' y='55%' font-size='10' font-family='Arial' fill='%23adb5bd' text-anchor='middle'>IMG</text></svg>";
                        @endphp

                        @forelse ($categories as $category)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $iconUrl = null;
                                            if ($category->icon_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->icon_path)) {
                                                $iconUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($category->icon_path);
                                            }
                                        @endphp
                                        <img src="{{ $iconUrl ?? $placeholderIcon }}" class="category-avatar shadow-sm" alt="{{ $category->name }}">
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $category->name }}</div>
                                            <span class="slug-badge">{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-muted small mb-0 text-wrap" style="max-width: 400px;">
                                        {{ $category->description ?? 'Tidak ada deskripsi tersedia.' }}
                                    </p>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button 
                                            class="btn btn-action btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            data-update-url="{{ route('admin.kategori.update', $category) }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.kategori.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-action btn-outline-danger" type="submit" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags fs-1 opacity-25 d-block mb-3"></i>
                                    Belum ada kategori yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->hasPages())
            <div class="card-footer bg-white py-3 px-4">
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="categoryForm" method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Konser Musik" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan jenis event ini..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Icon Kategori</label>
                        <input type="file" name="icon" accept="image/*" class="form-control">
                        <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks 2MB.</small>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Perbarui Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Ubah Icon</label>
                        <input type="file" name="icon" accept="image/*" class="form-control">
                        <small class="text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengubah icon.</small>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Logic Modal Edit Dinamis (Tetap Dipertahankan)
    const editModal = document.getElementById('editCategoryModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const updateUrl = button.getAttribute('data-update-url');
            const name = button.getAttribute('data-name') || '';
            const description = button.getAttribute('data-description') || '';

            const form = document.getElementById('editCategoryForm');
            form.action = updateUrl;
            form.querySelector('input[name="name"]').value = name;
            form.querySelector('textarea[name="description"]').value = description;
        });
    }
</script>
@endpush