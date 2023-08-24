<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Menggunakan tampilan login
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register'); // Menggunakan tampilan pendaftaran
    }
    
    public function register(Request $request)
    {
        // Validasi data
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    
        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Enkripsi password
        ]);
    
        // Login user setelah pendaftaran
        Auth::login($user);
    
        // Redirect ke halaman yang sesuai
        return redirect('/');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Jika berhasil login
            return redirect('/');
        } else {
            // Jika gagal login
            return back()->withErrors(['message' => 'Invalid credentials']);
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout(); // Keluar (logout) pengguna
        $request->session()->invalidate(); // Memadamkan sesi
        $request->session()->regenerateToken(); // Membuat token sesi baru
    
        return redirect('/'); // Redirect ke halaman login setelah keluar
    }
}
