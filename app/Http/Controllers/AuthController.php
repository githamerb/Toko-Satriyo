<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * ===============================
     * 🟢 TAMPILKAN FORM LOGIN
     * ===============================
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ===============================
     * 🟢 PROSES LOGIN
     * ===============================
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                                     ->with('success', 'Selamat datang, Admin!');

                case 'kasir':
                    return redirect()->route('kasir.index')
                                     ->with('success', 'Selamat datang, Kasir!');

                default:
                    Auth::logout();
                    return redirect()->route('login')
                                     ->with('error', 'Role pengguna tidak dikenali.');
            }
        }

        // Jika gagal login
        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * ===============================
     * 🔴 LOGOUT
     * ===============================
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Anda berhasil logout.');
    }

    /**
     * ===============================
     * 🟢 TAMPILKAN FORM REGISTER
     * ===============================
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * ===============================
     * 🟢 PROSES REGISTER USER BARU
     * ===============================
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,kasir',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
                         ->with('success', 'Akun berhasil dibuat!');
    }
}
