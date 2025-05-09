<?php

namespace App\Http\Controllers;

use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use Illuminate\Http\Request;

class PelangganBaruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Pelanggan Baru';
        return view('dashboard.pages.pelanggan.pelanggan-baru')->with(compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $petugases = Petugas::all();
        $kondisis = KondisiMeter::all();
        $page = 'Tambah Pelanggan';
        return view('dashboard.pages.data-pelanggan.create')->with(compact('page', 'petugases', 'kondisis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        $page = 'Edit Pelanggan';
        return view('dashboard.pages.data-pelanggan.edit')->with(compact('page', 'pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
