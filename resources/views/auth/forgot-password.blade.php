<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="h4">Reset Password</h2>
        <p class="text-muted small mt-2">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="needs-validation">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a class="text-decoration-none small text-muted" href="{{ route('login') }}">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to login
            </a>
            <button type="submit" class="btn btn-primary px-4 bg-primary-acetel border-0">
                Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
