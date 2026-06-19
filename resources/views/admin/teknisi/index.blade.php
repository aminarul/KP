@extends('layouts.admin')

@section('title', 'Manajemen Teknisi')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Master Data</div>
                <h2 class="page-title">Data Teknisi</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.teknisi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Teknisi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Teknisi</div>
                <div class="card-actions">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari teknisi..."
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
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Wilayah</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teknisis as $index => $teknisi)
                        <tr>
                            <td>{{ $teknisis->firstItem() + $index }}</td>
                            <td>{{ $teknisi->kode_teknisi }}</td>
                            <td>{{ $teknisi->user->name }}</td>
                            <td>{{ $teknisi->user->email }}</td>
                            <td>{{ $teknisi->wilayah }}</td>
                            <td>
                                <span class="badge bg-{{ $teknisi->user->is_active ? 'success' : 'danger' }}">
                                    {{ $teknisi->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $teknisi->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.teknisi.edit', $teknisi->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete-{{ $teknisi->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data teknisi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $teknisis->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($teknisis as $teknisi)
<div class="modal modal-blur fade" id="modal-delete-{{ $teknisi->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h3>Hapus Teknisi?</h3>
                <p>Apakah Anda yakin ingin menghapus teknisi <strong>{{ $teknisi->user->name }}</strong>?</p>
                <p class="text-muted">Ini akan menghapus akun login teknisi juga.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.teknisi.destroy', $teknisi->id) }}" method="POST">
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