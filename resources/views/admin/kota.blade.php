@extends('layouts.admin')

@section('title', 'Kelola Kota')
@section('page-title', 'Kelola Kota')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #6366f1;
        --border-radius-lg: 1rem;
    }

    /* 1. HEADER & STATS CARD */
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
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }

    /* 2. TABLE / LIST STYLING */
    .admin-data-card {
        border: none;
        border-radius: var(--border-radius-lg);
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
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 20px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* Thumbnail Kota */
    .city-thumbnail {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .city-placeholder {
        width: 64px;
        height: 64px;
        background-color: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    /* Event Count Badge */
    .event-count-badge {
        background-color: #eef2ff;
        color: var(--primary-color);
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid #e0e7ff;
    }

    /* Action Buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* 3. MODAL STYLING */
    .modal-content-custom {
        border-radius: 1.5rem;
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 30px;
    }
    .form-control-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        border-radius: 10px;
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
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 rounded-3 p-3" role="alert">
            <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 p-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div class="fw-bold">Mohon Periksa Kembali:</div>
            </div>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER SECTION --}}
    <div class="header-card shadow-sm">
        <div>
            <h4 class="fw-bold text-dark mb-1">Manajemen Kota</h4>
            <p class="text-muted small mb-0">Atur kota tujuan agar user mudah menemukan event di lokasi terdekat.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCityModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kota
        </button>
    </div>

    {{-- DATA TABLE --}}
    <div class="card admin-data-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="100">Preview</th>
                            <th>Detail Kota</th>
                            <th>Deskripsi Singkat</th>
                            <th class="text-center">Total Event</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td class="ps-4">
                                    {{-- LOGIKA GAMBAR STORAGE HELPER --}}
                                    @if($city->image_path)
                                        @php
                                            $cityImgUrl = str_starts_with($city->image_path, 'http')
                                                ? $city->image_path
                                                : \App\Helpers\StorageHelper::url($city->image_path);
                                        @endphp
                                        <img src="{{ $cityImgUrl }}" alt="{{ $city->name }}" class="city-thumbnail">
                                    @else
                                        <div class="city-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $city->name }}</div>
                                    <code class="small text-muted bg-light px-2 py-1 rounded">{{ $city->slug }}</code>
                                </td>
                                <td>
                                    <span class="text-muted small text-wrap" style="max-width: 300px; display: block; line-height: 1.5;">
                                        {{ Str::limit($city->description ?? 'Tidak ada deskripsi.', 80) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="event-count-badge">
                                        <i class="bi bi-calendar-event me-1"></i> {{ $city->events_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button 
                                            class="btn btn-action btn-outline-primary border-0 bg-primary bg-opacity-10 text-primary" 
                                            onclick="editCity({{ $city->id }})" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCityModal"
                                            title="Edit Kota">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.kota.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kota ini? Event terkait mungkin akan terdampak.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger" title="Hapus Kota">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-geo-alt text-muted opacity-25" style="font-size: 4rem;"></i>
                                        <p class="text-muted mt-3 mb-1 fw-bold">Belum Ada Data Kota</p>
                                        <p class="text-muted small">Silakan tambahkan kota baru untuk memulai.</p>
                                    </div>
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
<div class="modal fade" id="addCityModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pin-map-fill text-primary me-2"></i>Tambah Kota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.kota.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-custom" name="name" placeholder="Contoh: Jakarta" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Kota</label>
                        <textarea class="form-control form-control-custom" name="description" rows="3" placeholder="Jelaskan daya tarik kota ini..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Gambar Kota</label>
                        <input type="file" class="form-control form-control-custom" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2 fst-italic"><i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks 2MB.</small>
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

{{-- MODAL EDIT KOTA --}}
<div class="modal fade" id="editCityModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Kota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editCityForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kota</label>
                        <input type="text" class="form-control form-control-custom" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea class="form-control form-control-custom" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Ubah Gambar</label>
                        
                        {{-- Preview Gambar Saat Ini --}}
                        <div id="current_image_preview" class="mb-3 p-3 bg-light rounded-3 text-center border border-dashed"></div>
                        
                        <input type="file" class="form-control form-control-custom" id="edit_image" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2 fst-italic">Biarkan kosong jika tidak ingin mengubah gambar.</small>
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

{{-- JAVASCRIPT --}}
@php
    $citiesJson = $cities->map(function($city) {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'description' => $city->description,
            'image_url' => $city->image_path 
                ? (str_starts_with($city->image_path, 'http') ? $city->image_path : \App\Helpers\StorageHelper::url($city->image_path))
                : null
        ];
    })->values();
@endphp
<script>
    // Data dari Controller ditransfer ke JS dengan aman
    const citiesData = @json($citiesJson);

    function editCity(cityId) {
        const city = citiesData.find(c => c.id === cityId);
        if (!city) return;

        // Set Action Form
        document.getElementById('editCityForm').action = `/admin/kota/${cityId}`;

        // Isi Input
        document.getElementById('edit_name').value = city.name;
        document.getElementById('edit_description').value = city.description || '';

        // Tampilkan Preview Gambar
        const previewDiv = document.getElementById('current_image_preview');
        
        if (city.image_url) {
            previewDiv.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${city.image_url}" alt="${city.name}" class="shadow-sm rounded-3" style="max-height: 120px; max-width: 100%; object-fit: cover;">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-white shadow-sm">
                        Saat Ini
                    </span>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `
                <div class="py-3 text-muted">
                    <i class="bi bi-image fs-1 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada gambar terpasang.</p>
                </div>
            `;
        }
    }
</script>
@endsection