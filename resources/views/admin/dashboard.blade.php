@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="card admin-hero mb-4">
    <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="mb-3 mb-md-0">
            <span class="admin-hero-badge">Panel Terkini</span>
            <h2 class="admin-hero-title">Halo, {{ Auth::user()->name }} 👋</h2>
            <p class="text-white-50 mb-0">Pantau event, kategori, dan iklan yang sedang berjalan hari ini.</p>
        </div>
        <div class="admin-hero-progress text-center">
            <p class="text-white-50 small mb-1">Keterisian Jadwal</p>
            <div class="admin-progress-circle">
                <span>82%</span>
            </div>
            <small class="text-white-50">12 / 15 slot terisi</small>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="admin-quick-card">
            <div>
                <p class="text-muted small mb-1">Event Baru</p>
                <h4 class="mb-0">+4 minggu ini</h4>
            </div>
            <div class="admin-pill-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="admin-quick-card">
            <div>
                <p class="text-muted small mb-1">Pengajuan Iklan</p>
                <h4 class="mb-0">2 menunggu</h4>
            </div>
            <div class="admin-pill-icon">
                <i class="fa-solid fa-rocket"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="admin-quick-card">
            <div>
                <p class="text-muted small mb-1">Kategori Aktif</p>
                <h4 class="mb-0">6 kategori</h4>
            </div>
            <div class="admin-pill-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="admin-quick-card">
            <div>
                <p class="text-muted small mb-1">Tiket Terjual</p>
                <h4 class="mb-0">1.245</h4>
            </div>
            <div class="admin-pill-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card admin-stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="admin-stat-label mb-1">Total Event</p>
                    <p class="admin-stat-value mb-0">3</p>
                </div>
                <i class="fa-regular fa-calendar-days admin-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card admin-stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="admin-stat-label mb-1">Event Iklan</p>
                    <p class="admin-stat-value mb-0">1</p>
                </div>
                <i class="fa-solid fa-bullseye admin-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card admin-stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="admin-stat-label mb-1">Kategori</p>
                    <p class="admin-stat-value mb-0">3</p>
                </div>
                <i class="fa-solid fa-tags admin-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card admin-stat-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="admin-stat-label mb-1">Pengunjung Hari Ini</p>
                    <p class="admin-stat-value mb-0">123</p>
                </div>
                <i class="fa-solid fa-users admin-stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xxl-8">
        <div class="card admin-data-card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Data Event</h5>
                    <small class="text-muted">Daftar event aktif dan status iklan</small>
                </div>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Event
                </button>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Harga</th>
                            <th>Iklan</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Konser Musik Indie</td>
                            <td>Musik</td>
                            <td>2025-12-05</td>
                            <td>75.000</td>
                            <td><span class="badge bg-success">Ya</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateEventModal"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Pameran Seni Modern</td>
                            <td>Seni</td>
                            <td>2025-11-25</td>
                            <td>50.000</td>
                            <td><span class="badge bg-secondary">Tidak</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateEventModal"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Festival Kuliner Nusantara</td>
                            <td>Kuliner</td>
                            <td>2025-12-10</td>
                            <td>Gratis</td>
                            <td><span class="badge bg-secondary">Tidak</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateEventModal"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="card admin-side-card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aktivitas Terbaru</h6>
                <ul class="list-unstyled mb-0">
                    <li class="admin-activity-item">
                        <div class="admin-activity-dot bg-success"></div>
                        <div>
                            <p class="mb-0 fw-semibold">Event "Nada Senja" terbit</p>
                            <small class="text-muted">5 menit lalu</small>
                        </div>
                    </li>
                    <li class="admin-activity-item">
                        <div class="admin-activity-dot bg-warning"></div>
                        <div>
                            <p class="mb-0 fw-semibold">Pengajuan iklan Kuliner</p>
                            <small class="text-muted">20 menit lalu</small>
                        </div>
                    </li>
                    <li class="admin-activity-item">
                        <div class="admin-activity-dot bg-primary"></div>
                        <div>
                            <p class="mb-0 fw-semibold">Kategori "Komunitas" diperbarui</p>
                            <small class="text-muted">1 jam lalu</small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card admin-side-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Jadwal Event Minggu Ini</h6>
                <div class="admin-timeline-item">
                    <div>
                        <p class="fw-semibold mb-0">Selasa</p>
                        <small class="text-muted">12 Nov</small>
                    </div>
                    <div>
                        <p class="mb-0">Launch Festival Kuliner</p>
                        <small class="text-muted">Jakarta</small>
                    </div>
                    <span class="badge bg-soft-primary">Live</span>
                </div>
                <div class="admin-timeline-item">
                    <div>
                        <p class="fw-semibold mb-0">Kamis</p>
                        <small class="text-muted">14 Nov</small>
                    </div>
                    <div>
                        <p class="mb-0">Workshop Komunitas</p>
                        <small class="text-muted">Bandung</small>
                    </div>
                    <span class="badge bg-soft-warning">Persiapan</span>
                </div>
                <div class="admin-timeline-item">
                    <div>
                        <p class="fw-semibold mb-0">Sabtu</p>
                        <small class="text-muted">16 Nov</small>
                    </div>
                    <div>
                        <p class="mb-0">Konser Nada Senja</p>
                        <small class="text-muted">Jakarta</small>
                    </div>
                    <span class="badge bg-soft-success">Sold 80%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<p class="text-muted small text-center mt-5">© 2025 EventKita — Panel Admin</p>

{{-- Modal Tambah Event --}}
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Event Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="eventForm" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Event</label><input type="text" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Kategori</label><select class="form-select"><option>Musik</option><option>Seni</option><option>Kuliner</option></select></div>
            <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Harga Tiket (Rp)</label><input type="number" class="form-control"></div>
            <div class="col-md-12"><label class="form-label">Gambar Event</label><input type="file" class="form-control"></div>
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control" rows="3"></textarea></div>
            <div class="col-12 mt-2"><div class="form-check"><input type="checkbox" class="form-check-input"><label class="form-check-label">Tampilkan sebagai iklan</label></div></div>
          </div>
          <button type="submit" class="btn btn-primary-custom w-100 mt-3">Simpan Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Update Event --}}
<div class="modal fade" id="updateEventModal" tabindex="-1" aria-labelledby="updateEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title fw-bold">Update Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="updateEventForm">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Event</label><input type="text" class="form-control" value="Konser Musik Indie"></div>
            <div class="col-md-6"><label class="form-label">Kategori</label><select class="form-select"><option>Musik</option><option>Seni</option><option>Kuliner</option></select></div>
            <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" class="form-control" value="2025-12-05"></div>
            <div class="col-md-6"><label class="form-label">Harga Tiket (Rp)</label><input type="number" class="form-control" value="75000"></div>
             <div class="col-md-12"><label class="form-label">Gambar Event</label><input type="file" class="form-control"></div>
            <div class="col-md-12"><label class="form-label">Deskripsi</label><textarea class="form-control" rows="3">Konser musik indie dengan band lokal.</textarea></div>
            <div class="col-12 mt-2"><div class="form-check"><input type="checkbox" class="form-check-input" checked><label class="form-check-label">Tampilkan sebagai iklan</label></div></div>
          </div>
          <button type="submit" class="btn btn-primary-custom w-100 mt-3">Perbarui Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Kategori Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><form id="categoryForm">
            <div class="mb-3"><label class="form-label">Nama Kategori</label><input type="text" class="form-control" required></div>
            <button type="submit" class="btn btn-primary-custom w-100">Simpan Kategori</button>
        </form></div>
    </div></div>
</div>
@endsection
