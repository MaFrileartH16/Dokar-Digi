<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
  /**
   * Display the login view.
   */
  public function create(): View
  {
    return view('auth.login');
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }

  public function redirectToProvider($provider)
  {
    return Socialite::driver($provider)->redirect();
  }

  /**
   * Handle the OAuth callback.
   */
  public function handleProviderCallback($provider)
  {
    try {
      // Mendapatkan data pengguna dari penyedia OAuth
      $user = Socialite::driver($provider)->user();
    } catch (Exception $e) {
      return redirect()->route('login')->with('error', 'Terjadi kesalahan saat autentikasi dengan ' . ucfirst($provider));
    }

    // Validasi email - hanya email yang sudah terdaftar di aplikasi yang dapat login
    $existingUser = User::where('email', $user->getEmail())->first();

    if ($existingUser) {
      // Perbarui kolom profile_photo jika belum terisi
      if (!$existingUser->profile_photo) {
        $existingUser->profile_photo = $user->getAvatar(); // Menyimpan foto profil
        $existingUser->save();
      }

      // Lakukan login pengguna
      Auth::login($existingUser);

      // Redirect ke halaman yang sesuai setelah login
      return redirect()->intended(route('dashboard'));
    } else {
      // Pengguna dengan email tersebut tidak ditemukan, arahkan ke login
      return redirect()->route('login')->with('error', 'Email tidak terdaftar di Dokar Digi.');
    }
  }
}
