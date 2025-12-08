@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Daftar Kategori Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">Daftar Kategori</h1>
    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fa-solid fa-plus me-2"></i>Tambah Kategori
    </button>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $placeholderIcon = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64'><rect width='100%' height='100%' fill='%23e9ecef'/><text x='50%' y='55%' font-size='14' font-family='Arial' fill='%236c757d' text-anchor='middle'>ICON</text></svg>";
@endphp

<div class="card admin-data-card">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-nowrap mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $iconUrl = null;
                                    if ($category->icon_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->icon_path)) {
                                        $iconUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($category->icon_path);
                                    }
                                @endphp
                                <img src="{{ $iconUrl ?? $placeholderIcon }}" class="rounded-circle border" alt="{{ $category->name }}" width="48" height="48">
                                <div>
                                    <div class="fw-semibold">{{ $category->name }}</div>
                                    <small class="text-muted">Slug: {{ $category->slug }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-wrap" style="max-width: 360px;">{{ $category->description ?? '—' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button 
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal"
                                    data-update-url="{{ route('admin.kategori.update', $category) }}"
                                    data-name="{{ $category->name }}"
                                    data-description="{{ $category->description }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('admin.kategori.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada kategori. Tambahkan kategori baru untuk memulai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $categories->links() }}
    </div>
</div>

<p class="text-muted small text-center mt-5">© 2025 EventKita — Panel Admin</p>

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="categoryForm" method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
              <label class="form-label">Nama Kategori</label>
              <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Opsional"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Icon (opsional)</label>
              <input type="file" name="icon" accept="image/*" class="form-control">
              <small class="text-muted">Maks 2MB, format gambar.</small>
          </div>
          <button type="submit" class="btn btn-custom w-100">Simpan Kategori</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit Kategori --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title fw-bold">Perbarui Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="mb-3">
              <label class="form-label">Nama Kategori</label>
              <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Opsional"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Icon (opsional)</label>
              <input type="file" name="icon" accept="image/*" class="form-control">
              <small class="text-muted">Biarkan kosong jika tidak ingin mengubah icon.</small>
          </div>
          <button type="submit" class="btn btn-custom w-100">Perbarui Kategori</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
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
