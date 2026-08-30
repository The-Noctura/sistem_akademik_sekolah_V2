<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        
        return match($role) {
            'admin' => view('admin.dashboard'),
            'guru' => view('guru.dashboard'),
            'siswa' => view('siswa.dashboard'),
            default => abort(403),
        };
    }
}
