@extends('layouts.admin')

@section('title', 'Manajemen Coverage Area')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Master Data</div>
                <h2 class="page-title">Coverage Area / Wilayah Layanan</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.coverage.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Wilayah
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Wilayah Layanan</div>
                <div class="card-actions">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari wilayah..."
                            value="{{ request('search') }}" style="width: 250px;">
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Wilayah</th>
                            <th>Kode Pos</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $index => $area)
                        <tr>
                            <td>{{ $areas->firstItem() + $index }}</td>
                            <td>{{ $area->nama_wilayah }}</td>
                            <td>{{ $area->kode_pos ?? '-' }}</td>
                            <td>{{ Str::limit($area->keterangan, 50) }}</td>
                            <td>
                                <span class="badge bg-{{ $area->is_active ? 'success' : 'danger' }}">
                                    {{ $area->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $area->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.coverage.edit', $area->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coverage.toggle-status', $area->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-{{ $area->is_active ? 'warning' : 'success' }}">
                                        <i class="fas fa-{{ $area->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete-{{ $area->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data wilayah layanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $areas->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($areas as $area)
<div class="modal modal-blur fade" id="modal-delete-{{ $area->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h3>Hapus Wilayah?</h3>
                <p>Apakah Anda yakin ingin menghapus <strong>{{ $area->nama_wilayah }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.coverage.destroy', $area->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection