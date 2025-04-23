<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Petugas';
        return view('dashboard.pages.settings.petugas')->with(compact('page'));
    }

    public function data(Request $request)
    {
        $petugas = Petugas::select(['id', 'photo', 'nama', 'nip', 'no_hp1', 'no_hp2', 'email', 'username', 'tipe_pekerjaan', 'level', 'jenis_pekerjaan'])
            ->latest();

        return DataTables::of($petugas)
            ->addIndexColumn()
            ->addColumn('photo', function ($row) {
                $url = $row->photo ? asset('storage/pegawai/' . $row->photo) : asset('noimage.png');
                return '<img src="' . $url . '" width="50" class="img-thumbnail" />';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '"><i
                                            class="fas fa-pen-to-square"></i></button>';
                $deleteBtn = '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '"><i
                                            class="fas fa-trash"></i></button>';

                $editModal = '<div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="' . route('petugas.update', $row->id) . '" method="POST" enctype="multipart/form-data">
                                                        ' . csrf_field() . '
                                                        ' . method_field('PUT') . '
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Petugas</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3 align-items-center">
                                                                <div class="col-md-12" id="photoInputCol1' . $row->id . '">
                                                                    <div class="mb-3">
                                                                        <label for="photo1' . $row->id . '" class="form-label">Foto (opsional)</label>
                                                                        <input type="file" class="form-control" name="photo" id="photo1' . $row->id . '" accept="image/*"
                                                                            onchange="previewPhoto1(event, ' . $row->id . ')">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4' . ($row->photo ? '' : ' d-none') . '" id="photoPreviewCol1' . $row->id . '">
                                                                    <div class="mb-3 position-relative d-inline-block">
                                                                        <img id="photoPreview1' . $row->id . '" src="' . asset('storage/pegawai/' . $row->photo) . '" alt="Preview Foto" class="img-thumbnail rounded-circle"
                                                                            style="width: 150px; height: 150px; object-fit: cover; object-position: center;">
                                                                        <button type="button"
                                                                            class="btn btn-danger rounded-circle border border-dark position-absolute top-0 end-0 m-1 d-flex justify-content-center align-items-center"
                                                                            style="width: 30px; height: 30px; font-size: 15px; line-height: 1; padding: 0;"
                                                                            onclick="removePreview1(' . $row->id . ')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="nama' . $row->id . '" class="form-label">Nama</label>
                                                                        <input type="text" class="form-control" name="nama" value="' . htmlspecialchars($row->nama) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="nip' . $row->id . '" class="form-label">NIP</label>
                                                                        <input type="text" class="form-control" name="nip" value="' . htmlspecialchars($row->nip) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="no_hp1' . $row->id . '" class="form-label">No HP 1</label>
                                                                        <input type="text" class="form-control" name="no_hp1" value="' . htmlspecialchars($row->no_hp1) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="no_hp2' . $row->id . '" class="form-label">No HP 2</label>
                                                                        <input type="text" class="form-control" name="no_hp2" value="' . htmlspecialchars($row->no_hp2) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="email' . $row->id . '" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" value="' . htmlspecialchars($row->email) . '">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="username' . $row->id . '" class="form-label">Username</label>
                                                                        <input type="text" class="form-control" name="username" value="' . htmlspecialchars($row->username) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="tipe_pekerjaan' . $row->id . '" class="form-label">Tipe Pekerjaan</label>
                                                                        <input type="text" class="form-control" name="tipe_pekerjaan" value="' . htmlspecialchars($row->tipe_pekerjaan) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="jenis_pekerjaan' . $row->id . '" class="form-label">Jenis Pekerjaan</label>
                                                                        <input type="text" class="form-control" name="jenis_pekerjaan" value="' . htmlspecialchars($row->jenis_pekerjaan) . '">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="level' . $row->id . '" class="form-label">Level</label>
                                                                        <select name="level" class="form-select" required>
                                                                            <option value="">-- Pilih Level --</option>
                                                                            <option value="0" ' . ($row->level == 0 ? 'selected' : '') . '>Admin</option>
                                                                            <option value="1" ' . ($row->level == 1 ? 'selected' : '') . '>Petugas</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>';


                $deleteModal = '<div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="' . route('petugas.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <div class="modal-header">
                                <h5 class="modal-title">Hapus Petugas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Yakin ingin menghapus petugas <strong>' . htmlspecialchars($row->nama) . '</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';

                return $editBtn . ' ' . $deleteBtn . $editModal . $deleteModal;
            })
            ->rawColumns(['action', 'photo'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nama' => 'required|string|max:50',
                'nip' => 'required|string|max:30|unique:petugas,nip',
                'no_hp1' => 'required|string|max:13',
                'no_hp2' => 'nullable|string|max:13',
                'email' => 'required|email|max:50|unique:petugas,email',
                'username' => 'required|string|max:30|unique:petugas,username',
                'password' => 'required|string|max:255',
                'tipe_pekerjaan' => 'required|string|max:40',
                'level' => 'required|integer|between:0,255',
                'jenis_pekerjaan' => 'required|string|max:35',
            ]);
            $photoName = null;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $baseName = $request->username . '_001';
                $photoName = $baseName . '.' . $extension;

                // Cegah overwrite: jika sudah ada file dengan nama sama, tambahkan timestamp
                if (Storage::exists('pegawai/' . $photoName)) {
                    $photoName = $baseName . '_' . time() . '.' . $extension;
                }

                $file->storeAs('pegawai/', $photoName);
            }

            Petugas::create([
                'photo' => $photoName,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp1' => $request->no_hp1,
                'no_hp2' => $request->no_hp2,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'tipe_pekerjaan' => $request->tipe_pekerjaan,
                'level' => $request->level,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
            ]);

            return redirect()->back()->with('success', 'Data petugas berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $request->validate([
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nama' => 'required|string|max:50',
                'nip' => 'required|string|max:30|unique:petugas,nip,' . $id,
                'no_hp1' => 'required|string|max:13',
                'no_hp2' => 'nullable|string|max:13',
                'email' => 'required|email|max:50|unique:petugas,email,' . $id,
                'username' => 'required|string|max:30|unique:petugas,username,' . $id,
                'tipe_pekerjaan' => 'required|string|max:40',
                'level' => 'required|integer|between:0,255',
                'jenis_pekerjaan' => 'required|string|max:35',
            ]);
            $petugas = Petugas::findOrFail($id);



            $photoName = $petugas->photo; // default pakai foto lama

            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($photoName && Storage::exists('pegawai/' . $photoName)) {
                    Storage::delete('pegawai/' . $photoName);
                }

                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $baseName = $request->username . '_001';
                $photoName = $baseName . '.' . $extension;

                // Hindari overwrite
                if (Storage::exists('pegawai/' . $photoName)) {
                    $photoName = $baseName . '_' . time() . '.' . $extension;
                }

                $file->storeAs('pegawai/', $photoName);
            }



            $petugas->update([
                'photo' => $photoName,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp1' => $request->no_hp1,
                'no_hp2' => $request->no_hp2,
                'email' => $request->email,
                'username' => $request->username,
                'tipe_pekerjaan' => $request->tipe_pekerjaan,
                'level' => $request->level,
                'jenis_pekerjaan' => $request->jenis_pekerjaan,
            ]);

            return redirect()->back()->with('update', 'Data petugas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari data petugas berdasarkan ID
            $petugas = Petugas::findOrFail($id);

            // Hapus data petugas
            $petugas->delete();

            // Hapus foto jika ada di storage
            if ($petugas->photo && file_exists(storage_path('app/public/pegawai/' . $petugas->photo))) {
                unlink(storage_path('app/public/pegawai/' . $petugas->photo)); // Hapus foto dari storage
            }

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('delete', 'Petugas berhasil dihapus.');
        } catch (\Exception $e) {
            // Menangani jika terjadi error, dan mengarahkan kembali dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus petugas: ' . $e->getMessage());
        }
    }
}
