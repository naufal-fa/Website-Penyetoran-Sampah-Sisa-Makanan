@extends('layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-light">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}. Ini adalah ringkasan sistem hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Total Nasabah</h5>
                    <p class="card-text fs-2 fw-bold">{{ $totalNasabah }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-dark bg-warning shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-seam me-2"></i>Setoran Pending</h5>
                    <p class="card-text fs-2 fw-bold">{{ $pendingDepositsCount }}</p>
                    <a href="{{ route('admin.deposits.index') }}" class="text-dark stretched-link">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-danger shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cash-coin me-2"></i>Penarikan Pending</h5>
                    <p class="card-text fs-2 fw-bold">{{ $pendingWithdrawalsCount }}</p>
                    <a href="{{ route('admin.withdrawals.index') }}" class="text-white stretched-link">Proses Sekarang</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-success shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-trash3-fill me-2"></i>Sampah Terkumpul Hari Ini</h5>
                    <p class="card-text fs-2 fw-bold">{{ number_format($wasteCollectedToday, 1) }} kg</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="fw-light mt-4 mb-3">Aktivitas Setoran Terbaru</h4>
            <div class="card shadow-sm">
                <ul class="list-group list-group-flush">
                    @forelse($latestActivities as $activity)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-info-circle-fill text-muted me-2"></i>
                                Nasabah <strong>{{ $activity->user->name }}</strong> melaporkan setoran
                                <strong class="text-primary">{{ $activity->weight_kg }} kg</strong>.
                            </div>
                            <span class="badge rounded-pill text-bg-{{ $activity->status == 'completed' ? 'success' : ($activity->status == 'pending_verification' ? 'warning' : 'danger') }}">{{ str_replace('_', ' ', $activity->status) }}</span>
                        </li>
                    @empty
                         <li class="list-group-item text-center text-muted">Belum ada aktivitas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection