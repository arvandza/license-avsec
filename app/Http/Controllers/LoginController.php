<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Flasher\Notyf\Prime\notyf;

class LoginController extends Controller
{
    public function index()
    {
        $auth = Auth::check();

        if ($auth) {
            return $this->redirectToRole();
        }
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:3'
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return $this->redirectToRole();
            }

            notyf()->error('Email atau Password yang anda masukkan salah');
            return redirect()->route('login');
        } catch (\Throwable $th) {
            notyf()->error($th->getMessage());
            return redirect()->route('login');
        }
    }

    protected function redirectToRole()
    {
        try {
            $user = Auth::user();

            if ($user->role->name === 'Pegawai') {
                return redirect()->route('pegawais.index');
            } else if ($user->role->name === 'Admin') {
                notyf()->success('Selamat Datang, ' . $user->employee->fullname);
                return redirect()->route('dashboard');
            }
            notyf()->error('Anda tidak memiliki hak akses');
            return redirect()->route('login');
        } catch (\Throwable $th) {
            notyf()->error($th->getMessage());
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
