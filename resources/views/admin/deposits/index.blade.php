@extends('layout.app')

@section('title', 'Manajemen Deposit')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2 class="fw-light">Setoran Menunggu Persetujuan</h2>
            <p class="text-muted">Setujui laporan setoran dari nasabah di bawah ini untuk menambahkan saldo ke akun mereka.</p>
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
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Kode Setoran</th>
                            <th scope="col">Tanggal Lapor</th>
                            <th scope="col">Nasabah</th>
                            <th scope="col">Berat (kg)</th>
                            <th scope="col">Nilai (Rp)</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingDeposits as $deposit)
                            <tr>
                                <td class="font-monospace fw-bold">{{ $deposit->deposit_code }}</td>
                                <td>{{ $deposit->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $deposit->user->name }}</td>
                                <td>{{ $deposit->weight_kg }}</td>
                                <td>{{ number_format($deposit->total_value, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.deposits.approve', $deposit->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui setoran ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle me-1"></i> Setujui
                                        </button>
                                    </form>
                                    {{-- Anda juga bisa menambahkan tombol "Tolak" di sini --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada setoran yang menunggu persetujuan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pendingDeposits->hasPages())
            <div class="card-footer">
                {{ $pendingDeposits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection