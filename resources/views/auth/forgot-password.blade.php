@extends('layouts.app')

@section('title', 'Lupa Kata Sandi')

@section('content')
  <div class="page page-center">
    <div class="container container-tight py-4">
      <form class="card card-md" method="POST" action="{{ route('password.email') }}" autocomplete="off" novalidate>
        @csrf

        <div class="card-body">
          <h2 class="card-title text-center mb-4">Lupa Kata Sandi</h2>

          @if (session('status'))
            <div class="alert alert-success mb-4">
              {{ session('status') }}
            </div>
          @endif

          <p class="text-secondary mb-4">Masukkan alamat surel Anda dan kata sandi Anda akan disetel ulang dan dikirim
            melalui surel kepada Anda.</p>

          <div class="mb-3">
            <label class="form-label">Alamat surel</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="{{ old('email') }}" required autofocus placeholder="Masukkan surel"/>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="icon icon-2">
                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                <path d="M3 7l9 6l9 -6"/>
              </svg>
              Kirimi saya kata sandi baru
            </button>
          </div>
        </div>
      </form>
      <div class="text-center text-secondary mt-3">Lupakan, <a href="{{ route('login') }}">kirim saya kembali</a> ke
        layar masuk.
      </div>
    </div>
  </div>
@endsection
