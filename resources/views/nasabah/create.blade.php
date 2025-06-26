@extends('layout.app')

@section('title', 'Penarikan Dana')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Formulir Penarikan Dana</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        Saldo Anda Saat Ini: 
                        <strong class="fs-5">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</strong>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('nasabah.withdrawals.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Penarikan (Minimal Rp 10.000)</label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="Contoh: 50000" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="destination_details" class="form-label">Tujuan Penarikan</label>
                            <textarea name="destination_details" id="destination_details" class="form-control @error('destination_details') is-invalid @enderror" rows="3" placeholder="Contoh: Bank BCA - 123456789 a/n Budi Santoso" required>{{ old('destination_details') }}</textarea>
                            <div class="form-text">Masukkan nomor rekening bank atau e-wallet Anda.</div>
                             @error('destination_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ajukan Penarikan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <h4 class="fw-light mb-3">Riwayat Penarikan Dana Anda</h4>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Jumlah</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                    <td>{{ $withdrawal->destination_details }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-{{ $withdrawal->status == 'processed' ? 'success' : ($withdrawal->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat penarikan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($withdrawals->hasPages())
                    <div class="card-footer">
                        {{ $withdrawals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection