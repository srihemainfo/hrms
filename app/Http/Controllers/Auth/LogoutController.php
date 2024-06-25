<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logoutRIT(Request $request)
    {
        if (session('appUser') == false) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        } else {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return view('mobileHome');
        }
    }
}
