<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PetaPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Peta Pelanggan';
        
        $pelanggans = Pelanggan::whereNotNull('lat')
            ->whereNotNull('long')
            ->where('status', 'aktif')
            ->get();
            
        return view('dashboard.pages.pelanggan.peta', compact('page', 'pelanggans'));
    }

    /**
     * Get data for map (JSON API)
     */
    public function data()
    {
        $pelanggans = Pelanggan::whereNotNull('lat')
            ->whereNotNull('long')
            ->where('status', 'aktif')
            ->with(['wilayah', 'golongan'])
            ->get();
            
        return response()->json($pelanggans);
    }
}