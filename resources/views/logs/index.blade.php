@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Log Aktivitas Sistem</h5>
                    <div>
                        <a href="{{ route('logs.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('logs.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Modul</label>
                                    <select name="module" class="form-control">
                                        <option value="">Semua Modul</option>
                                        <option value="hr" {{ request('module') == 'hr' ? 'selected' : '' }}>HR</option>
                                        <option value="production" {{ request('module') == 'production' ? 'selected' : '' }}>Produksi</option>
                                        <option value="inventory" {{ request('module') == 'inventory' ? 'selected' : '' }}>Gudang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipe Aktivitas</label>
                                    <select name="activity_type" class="form-control">
                                        <option value="">Semua Tipe</option>
                                        <option value="create" {{ request('activity_type') == 'create' ? 'selected' : '' }}>Tambah</option>
                                        <option value="update" {{ request('activity_type') == 'update' ? 'selected' : '' }}>Ubah</option>
                                        <option value="delete" {{ request('activity_type') == 'delete' ? 'selected' : '' }}>Hapus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('logs.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Logs Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                    <th>Modul</th>
                                    <th>Aktivitas</th>
                                    <th>Deskripsi</th>
                                    <th>IP Address</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ ucfirst($log->module) }}</td>
                                    <td>{{ ucfirst($log->activity_type) }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        <a href="{{ route('logs.show', $log) }}" class="btn btn-sm btn-info">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection