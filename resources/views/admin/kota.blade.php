@extends('layouts.admin')

@section('title', 'Kelola Kota')
@section('page-title', 'Kelola Kota')

@section('content')
<style>
    /* Custom Styling untuk Manajemen Kota */
    .city-thumbnail {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .city-icon-placeholder {
        width: 80px;
        height: 50px;
        background-color: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.2rem;
    }
    .admin-data-card {
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        background: white;
        overflow: hidden;
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
    .event-count-badge {
        background-color: #eef2ff;
        color: #6366f1;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
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
</style>

<div class="container-fluid pb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Manajemen Kota</h4>
            <p class="text-muted small mb-0">Atur kota tujuan agar user mudah menemukan event di lokasi terdekat.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCityModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kota
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 1rem;">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 1rem;">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div class="fw-bold">Mohon Periksa Kembali:</div>
            </div>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
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
                            <th class="ps-4">Preview</th>
                            <th>Detail Kota</th>
                            <th>Deskripsi Singkat</th>
                            <th class="text-center">Aktivitas</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td class="ps-4">
                                    @if($city->image_path)
                                        <img src="{{ Storage::url($city->image_path) }}" alt="{{ $city->name }}" class="city-thumbnail shadow-sm">
                                    @else
                                        <div class="city-icon-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $city->name }}</div>
                                    <code class="small text-muted">{{ $city->slug }}</code>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ Str::limit($city->description ?? 'Tidak ada deskripsi.', 60) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="event-count-badge">
                                        <i class="bi bi-calendar-event me-1"></i> {{ $city->events_count ?? 0 }} Event
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="editCity({{ $city->id }})" data-bs-toggle="modal" data-bs-target="#editCityModal">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.kota.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-geo-alt fs-1 opacity-25"></i>
                                    </div>
                                    <p class="fw-bold mb-1">Belum Ada Data Kota</p>
                                    <p class="small mb-0">Klik tombol "Tambah Kota" untuk mulai mengisi database lokasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KOTA --}}
<div class="modal fade" id="addCityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">📍 Tambah Kota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Nama Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contoh: Jakarta" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi Kota</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Jelaskan sedikit tentang daya tarik kota ini..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Gambar/Foto Kota</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Simpan Kota</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KOTA --}}
<div class="modal fade" id="editCityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">✏️ Edit Data Kota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCityForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Kota</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubah Gambar Kota</label>
                        <div id="current_image_preview" class="mb-3 p-2 bg-light rounded text-center"></div>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti gambar.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Data cities tetap menggunakan data dari backend
    const citiesData = @json($cities);

    function editCity(cityId) {
        const city = citiesData.find(c => c.id === cityId);
        if (!city) return;

        // Set Action URL
        document.getElementById('editCityForm').action = `/admin/kota/${cityId}`;
        
        // Populate Fields
        document.getElementById('edit_name').value = city.name;
        document.getElementById('edit_description').value = city.description || '';
        
        // Current Image Preview Logic
        const previewDiv = document.getElementById('current_image_preview');
        if (city.image_path) {
            previewDiv.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="/storage/${city.image_path}" alt="${city.name}" class="img-thumbnail" style="max-height: 120px; border-radius: 10px;">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary shadow-sm">Aktif</span>
                </div>
                <p class="small text-muted mt-2 mb-0">Gambar saat ini terpasang</p>
            `;
        } else {
            previewDiv.innerHTML = `
                <div class="py-3 text-muted">
                    <i class="bi bi-image fs-2 d-block"></i>
                    <p class="small mb-0">Belum ada gambar terpasang</p>
                </div>
            `;
        }
    }
</script>
@endsection