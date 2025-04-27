@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="card card-md">
        <div class="card-body">
          <h2 class="h2 text-center mb-4">Masuk ke akun Anda</h2>
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
              <label class="form-label">Alamat surel</label>
              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                     autofocus autocomplete="email" placeholder="surel@anda.com"/>
              @error('email')
              <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-2">
              <label class="form-label">
                Kata sandi
                <span class="form-label-description">
                  <a href="{{ route('password.request') }}">Lupa kata sandi</a>
                </span>
              </label>
              <div class="input-group input-group-flat">
                <input id="password" type="password" class="form-control" name="password" required
                       autocomplete="current-password" placeholder="Kata sandi Anda"/>
                <span class="input-group-text">
                  <a href="#" class="link-secondary toggle-password" title="Tampilkan kata sandi"
                     data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-1">
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                    </svg>
                  </a>
                </span>
              </div>
              @error('password')
              <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-2">
              <label class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember"/>
                <span class="form-check-label">Ingat saya di perangkat ini</span>
              </label>
            </div>

            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </div>
          </form>
        </div>
        <div class="hr-text">atau</div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <a class="btn btn-4 w-100" href="{{ route('login.provider', 'google') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                     class="icon icon-tabler icons-tabler-filled icon-tabler-brand-google">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path
                    d="M12 2a9.96 9.96 0 0 1 6.29 2.226a1 1 0 0 1 .04 1.52l-1.51 1.362a1 1 0 0 1 -1.265 .06a6 6 0 1 0 2.103 6.836l.001 -.004h-3.66a1 1 0 0 1 -.992 -.883l-.007 -.117v-2a1 1 0 0 1 1 -1h6.945a1 1 0 0 1 .994 .89c.04 .367 .061 .737 .061 1.11c0 5.523 -4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10z"/>
                </svg>
                Masuk dengan Google
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.querySelectorAll('.toggle-password').forEach(function (element) {
      element.addEventListener('click', function (e) {
        e.preventDefault();
        const passwordInput = this.closest('.input-group').querySelector('input');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        const icon = this.querySelector('svg');
        if (type === 'password') {
          icon.innerHTML = '<path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>';
        } else {
          icon.innerHTML = '<path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/>';
        }
      });
    });
  </script>
@endpush
