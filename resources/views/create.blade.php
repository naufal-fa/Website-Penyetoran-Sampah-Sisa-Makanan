@extends('layouts.app')

@section('title', 'Input Setoran Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h4>Verifikasi Setoran Sampah Baru</h4>
            </div>
            <div class="card-body">

                {{-- Menampilkan pesan sukses/error --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.deposits.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Pilih Nasabah</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Nasabah --</option>
                            @foreach($nasabah as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="weight_kg" class="form-label">Masukkan Berat Sampah (kg)</label>
                        <input type="number" step="0.1" name="weight_kg" id="weight_kg" class="form-control @error('weight_kg') is-invalid @enderror" placeholder="Contoh: 5.5" required>
                         @error('weight_kg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Konfirmasi & Simpan Setoran</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection