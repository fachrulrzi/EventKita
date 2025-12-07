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

<div class="card admin-data-card">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-nowrap mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Musik</td>
                    <td>Event konser, festival musik, dan pertunjukan live.</td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-confirm-delete>
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Seni</td>
                    <td>Pameran seni dan kegiatan kreatif.</td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-confirm-delete>
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Kuliner</td>
                    <td>Festival makanan, bazar kuliner, dan demo masak.</td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-confirm-delete>
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
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
        <form id="categoryForm">
          <div class="mb-3">
              <label class="form-label">Nama Kategori</label>
              <input type="text" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" rows="3"></textarea>
          </div>
          <button type="submit" class="btn btn-custom w-100">Simpan Kategori</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Update Kategori --}}
<div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title fw-bold">Update Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="updateCategoryForm">
          <div class="mb-3">
              <label class="form-label">Nama Kategori</label>
              <input type="text" class="form-control" value="Musik">
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" rows="3">Event konser dan festival musik.</textarea>
          </div>
          <button type="submit" class="btn btn-custom w-100">Perbarui Kategori</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
