@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-light">Selamat Datang, <span class="fw-bold">{{ Auth::user()->name }}!</span></h2>
            <p class="text-muted">Ini adalah ringkasan aktivitas Bank Sampah Anda.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card text-white bg-success shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-uppercase mb-0">Saldo Saat Ini</h5>
                            <p class="card-text display-5 fw-bold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
                        </div>
                        <div>
                             <i class="bi bi-wallet2" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                     <a href="{{ route('nasabah.withdrawals.create') }}" class="btn btn-light mt-3">Ajukan Penarikan Dana <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Sampah Disetor</h6>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalWeight, 1, ',', '.') }} kg</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Pendapatan Diterima</h6>
                    <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Dana Ditarik</h6>
                    <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-12">
            <div class="card text-center shadow-sm p-4 bg-light border-2">
                <div class="card-body">
                    <h3 class="card-title">Punya Sampah Untuk Disetor?</h3>
                    <p class="card-text text-muted">Laporkan setoran sampah Anda sekarang untuk mendapatkan kode dan mempercepat proses di lokasi pengumpulan.</p>
                    <a href="{{ route('nasabah.lapor.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-down me-2"></i>Lapor Setoran Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="fw-light mb-3">5 Transaksi Terakhir</h4>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        {{-- ... Isi tabel riwayat transaksi tidak berubah ... --}}
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col" class="text-end">Debit (Masuk)</th>
                                <th scope="col" class="text-end">Kredit (Keluar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if ($transaction instanceof \App\Models\WasteDeposit)
                                            Setoran Sampah ({{ $transaction->weight_kg }} kg)
                                            <span class="badge rounded-pill text-bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending_verification' ? 'warning' : 'danger') }}">{{ str_replace('_', ' ', $transaction->status) }}</span>
                                        @elseif ($transaction instanceof \App\Models\Withdrawal)
                                            Penarikan Dana 
                                            <span class="badge rounded-pill text-bg-{{ $transaction->status == 'processed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }}">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        @if ($transaction instanceof \App\Models\WasteDeposit && $transaction->status == 'approved')
                                            + Rp {{ number_format($transaction->total_value, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="text-end text-danger fw-bold">
                                        @if ($transaction instanceof \App\Models\Withdrawal)
                                            - Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection