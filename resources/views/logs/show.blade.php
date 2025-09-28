@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Log Aktivitas</h5>
                    <a href="{{ route('logs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Tanggal</th>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <th>Modul</th>
                                    <td>{{ ucfirst($log->module) }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Aktivitas</th>
                                    <td>{{ ucfirst($log->activity_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $log->description }}</td>
                                </tr>
                                <tr>
                                    <th>IP Address</th>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th>User Agent</th>
                                    <td>{{ $log->user_agent }}</td>
                                </tr>
                            </table>
                        </div>

                        @if($log->old_data || $log->new_data)
                        <div class="col-md-6">
                            @if($log->activity_type === 'update')
                                <h6>Perubahan Data:</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Data Lama</th>
                                                <th>Data Baru</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($log->new_data as $key => $value)
                                                @if(isset($log->old_data[$key]) && $log->old_data[$key] !== $value)
                                                    <tr>
                                                        <td>{{ ucfirst($key) }}</td>
                                                        <td>{{ is_array($log->old_data[$key]) ? json_encode($log->old_data[$key]) : $log->old_data[$key] }}</td>
                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6>Data {{ $log->activity_type === 'create' ? 'Baru' : 'Terhapus' }}:</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($log->activity_type === 'create' ? $log->new_data : $log->old_data) as $key => $value)
                                                <tr>
                                                    <td>{{ ucfirst($key) }}</td>
                                                    <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection