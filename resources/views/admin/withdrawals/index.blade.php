@extends('layout.app')

@section('title', 'Manajemen Penarikan')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2 class="fw-light">Manajemen Penarikan Dana</h2>
            <p class="text-muted">Proses permintaan penarikan dana dari nasabah. Pastikan Anda telah mentransfer dana secara manual sebelum menandai sebagai selesai.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Jumlah (Rp)</th>
                            <th>Tujuan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $withdrawal->user->name }}</td>
                                <td class="fw-bold">{{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                <td>{{ $withdrawal->destination_details }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill text-bg-{{ $withdrawal->status == 'processed' ? 'success' : ($withdrawal->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($withdrawal->status == 'pending')
                                        <div class="btn-group">
                                            <form action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="processed">
                                                <button type="submit" class="btn btn-sm btn-success" title="Tandai sebagai Selesai" onclick="return confirm('Anda yakin ingin menandai penarikan ini sebagai SELESAI? Pastikan dana sudah ditransfer.')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak Permintaan" onclick="return confirm('Anda yakin ingin MENOLAK permintaan ini? Saldo akan dikembalikan ke nasabah.')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <i class="bi bi-check-circle-fill text-success" title="Telah diproses pada {{ $withdrawal->processed_at }}"></i>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data penarikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($withdrawals->hasPages())
            <div class="card-footer">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection