<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Reotornar a vista de dashboard (aún no creada)
        return view('dashboard.index');
    }
}
