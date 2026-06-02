<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $userName = $user->petugas?->nama ?? ucfirst($user->name ?? 'Admin');

        $stats = [
            'total_pelanggan' => Pelanggan::count(),
            'catat_hari_ini' => CatatMeter::whereDate('waktu', today())->count(),
            'total_wilayah' => Wilayah::count(),
            'total_petugas' => Petugas::count(),
        ];

        return view('home', compact('userName', 'stats'));
    }
}
