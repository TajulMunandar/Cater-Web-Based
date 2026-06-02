<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Wilayah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetaPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $page = 'Peta Pelanggan';

            $wilayahs = Wilayah::all();

            return view('dashboard.pages.pelanggan.peta', compact('page', 'wilayahs'));
        } catch (Exception $e) {
            Log::error('Error in PetaPelangganController index: ' . $e->getMessage());
            abort(500, 'Internal server error');
        }
    }

    /**
     * Get data for map (JSON API)
     */
    public function data(Request $request)
    {
        try {
            $query = Pelanggan::whereNotNull('lat')
                ->whereNotNull('long')
                ->with(['wilayah', 'golongan']);

            if ($request->has('wilayah') && $request->wilayah) {
                $query->where('id_wilayah', $request->wilayah);
            }

            $pelanggans = $query->get();

            return response()->json($pelanggans);
        } catch (Exception $e) {
            Log::error('Error fetching pelanggan data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
