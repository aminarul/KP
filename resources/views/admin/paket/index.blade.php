@extends('layouts.admin')

@section('title', 'Manajemen Paket Internet')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Master Data</div>
                <h2 class="page-title">Paket Internet</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.paket.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Paket
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Paket Internet</div>
                <div class="card-actions">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari paket..."
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
                            <th>Nama Paket</th>
                            <th>Kecepatan</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pakets as $index => $paket)
                        <tr>
                            <td>{{ $pakets->firstItem() + $index }}</td>
                            <td>{{ $paket->nama_paket }}</td>
                            <td>{{ $paket->speed }}</td>
                            <td>{{ $paket->harga_formatted }}</td>
                            <td>
                                <span class="badge bg-{{ $paket->status === 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($paket->status) }}
                                </span>
                            </td>
                            <td>{{ $paket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.paket.edit', $paket->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete-{{ $paket->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data paket internet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $pakets->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($pakets as $paket)
<div class="modal modal-blur fade" id="modal-delete-{{ $paket->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h3>Hapus Paket?</h3>
                <p>Apakah Anda yakin ingin menghapus paket <strong>{{ $paket->nama_paket }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.paket.destroy', $paket->id) }}" method="POST">
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