@extends('dashboard.partials.main')

@section('title', 'Profile')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Profile', 'subtitle' => 'Kelola informasi akun anda', 'icon' => 'user'])

<div class="row g-4">
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-modern alert-success d-flex align-items-center gap-2 mb-0">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Profile Card -->
    <div class="col-xl-4">
        <div class="card-modern p-4 text-center">
            <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle"
                 style="width:100px;height:100px;background:var(--color-primary);color:#fff;font-size:2.5rem;font-weight:700;box-shadow:0 4px 20px rgba(37,99,235,0.25);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="mt-3 mb-1 fw-bold" style="color:var(--color-text);">{{ $user->name }}</h5>
            <p class="mb-0" style="color:var(--color-text-muted);font-size:0.85rem;">{{ $user->email }}</p>
            <div class="mt-3 d-flex justify-content-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill" style="font-size:0.75rem;font-weight:500;">
                    @if($user->level == 1) Admin
                    @elseif($user->level == 2) Petugas
                    @else User
                    @endif
                </span>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" style="font-size:0.75rem;font-weight:500;">
                    <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i> Aktif
                </span>
            </div>
            <hr class="my-4" style="border-color:var(--color-border);">
            <div class="text-start small" style="color:var(--color-text-secondary);">
                <div class="d-flex justify-content-between mb-2">
                    <span>Username</span>
                    <span class="fw-medium" style="color:var(--color-text);">{{ $user->username }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Bergabung</span>
                    <span class="fw-medium" style="color:var(--color-text);">{{ $user->created_at?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="col-xl-8">
        <div class="card-modern p-4">
            <h5 class="fw-bold mb-1" style="color:var(--color-text);">Informasi Akun</h5>
            <p class="mb-4" style="color:var(--color-text-muted);font-size:0.85rem;">Perbarui informasi dasar akun anda</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium" style="font-size:0.85rem;color:var(--color-text-secondary);">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control form-control-modern @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium" style="font-size:0.85rem;color:var(--color-text-secondary);">Username</label>
                        <input type="text" name="username" class="form-control form-control-modern @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-medium" style="font-size:0.85rem;color:var(--color-text-secondary);">Email</label>
                        <input type="email" name="email" class="form-control form-control-modern @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-modern btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card-modern p-4 mt-4">
            <h5 class="fw-bold mb-1" style="color:var(--color-text);">Ubah Password</h5>
            <p class="mb-4" style="color:var(--color-text-muted);font-size:0.85rem;">Ganti password akun anda secara berkala</p>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium" style="font-size:0.85rem;color:var(--color-text-secondary);">Password Baru</label>
                        <input type="password" name="password" class="form-control form-control-modern @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium" style="font-size:0.85rem;color:var(--color-text-secondary);">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-modern" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-modern btn-primary px-4">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
