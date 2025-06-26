@extends('layout.app')
@section('title', 'Lapor Setoran Sampah')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Lapor Setoran Sampah</div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success text-center">
                        <p class="mb-2">{{ session('success') }}</p>
                        <div class="bg-white p-3 my-2 rounded border border-2">
                            <span class="text-muted d-block">Kode Setoran Anda:</span>
                            <h2 class="fw-bold text-primary mb-0" style="letter-spacing: 2px;">{{ session('deposit_code') }}</h2>
                        </div>
                        <small>Tunjukkan kode ini kepada petugas saat Anda menyerahkan sampah di lokasi.</small>
                    </div>

                    <hr>
                    <p class="text-center">
                        <a href="{{ route('nasabah.lapor.create') }}" class="btn btn-secondary">Lapor Setoran Lain</a>
                    </p>
                @else
                    <form action="{{ route('nasabah.lapor.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="weight_kg" class="form-label">Berat Sampah (kg)</label>
                            <input type="number" step="0.1" name="weight_kg" id="weight_kg" class="form-control @error('weight_kg') is-invalid @enderror" value="{{ old('weight_kg') }}" placeholder="Contoh: 1.8" required>
                             @error('weight_kg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan berat sampah sesuai hasil timbangan Anda.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Dapatkan Kode Setoran</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection