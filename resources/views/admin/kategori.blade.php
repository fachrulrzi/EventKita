@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Daftar Kategori Event')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #6366f1;
        --bg-soft: #f8fafc;
        --border-radius-lg: 1rem;
    }

    /* 1. HEADER SECTION */
    .header-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    /* 2. TABLE / LIST STYLING */
    .admin-data-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 700;
        color: #64748b;
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 20px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }
    .table tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* Avatar & Badge */
    .category-avatar {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        background-color: #e2e8f0;
    }
    .slug-badge {
        background-color: #e0e7ff;
        color: var(--primary-color);
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
    }

    /* Buttons */
    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* 3. MODAL FORM */
    .modal-content-custom {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 30px;
    }
    .form-control-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        border-radius: 10px;
        transition: 0.2s;
    }
    .form-control-custom:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
</style>

<div class="container-fluid pb-5">
    
    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4 rounded-3 d-flex align-items-center" role="alert">
            <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 p-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
                <strong>Terjadi Kesalahan:</strong>
            </div>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER & ACTIONS --}}
    <div class="header-card shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Kategori</h4>
            <p class="text-muted small mb-0">Kelola kategori event untuk mempermudah pencarian user.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
        </button>
    </div>

    {{-- DATA TABLE --}}
    <div class="card admin-data-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="60">#</th>
                            <th width="35%">Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $placeholderIcon = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64'><rect width='100%' height='100%' fill='%23f1f5f9'/><text x='50%' y='55%' font-size='12' font-family='Arial' fill='%2394a3b8' text-anchor='middle'>IMG</text></svg>";
                        @endphp

                        @forelse ($categories as $category)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- LOGIKA GAMBAR STORAGE HELPER --}}
                                        @php
                                            $iconUrl = null;
                                            if (!empty($category->icon_path)) {
                                                $iconUrl = \App\Helpers\StorageHelper::url($category->icon_path);
                                            }
                                        @endphp
                                        
                                        <img src="{{ $iconUrl ?? $placeholderIcon }}" class="category-avatar" alt="{{ $category->name }}">
                                        
                                        <div>
                                            <div class="fw-bold text-dark mb-1">{{ $category->name }}</div>
                                            <span class="slug-badge">{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-secondary small mb-0 text-wrap" style="max-width: 450px; line-height: 1.6;">
                                        {{ $category->description ?? 'Tidak ada deskripsi tersedia.' }}
                                    </p>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button 
                                            class="btn btn-action btn-outline-primary border-0 bg-primary bg-opacity-10 text-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            data-update-url="{{ route('admin.kategori.update', $category) }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            title="Edit Kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.kategori.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Data event terkait mungkin akan terdampak.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-action btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger" type="submit" title="Hapus Kategori">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-tags text-muted opacity-25" style="font-size: 4rem;"></i>
                                        <p class="text-muted mt-3 mb-0 fw-semibold">Belum ada kategori yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- PAGINATION --}}
        @if($categories->hasPages())
            <div class="card-footer bg-white border-top border-light py-4">
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-square text-primary me-2"></i>Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="categoryForm" method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="Contoh: Konser Musik" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control form-control-custom" rows="3" placeholder="Jelaskan jenis event ini..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Icon Kategori</label>
                        <input type="file" name="icon" accept="image/*" class="form-control form-control-custom">
                        <small class="text-muted mt-2 d-block fst-italic"><i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks 2MB.</small>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Perbarui Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kategori</label>
                        <input type="text" name="name" class="form-control form-control-custom" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-custom" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Ubah Icon</label>
                        <input type="file" name="icon" accept="image/*" class="form-control form-control-custom">
                        <small class="text-muted d-block mt-2 fst-italic">Biarkan kosong jika tidak ingin mengubah icon saat ini.</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Logic Modal Edit Dinamis (100% Logic Asli Dipertahankan)
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