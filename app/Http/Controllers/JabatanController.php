<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q');
        $jabatan = Jabatan::where('kode_jabatan', 'LIKE', "%$search%")
                        ->orWhere('nama_jabatan', 'LIKE', "%$search%")
                        ->get();

        return response()->json($jabatan);
    }
    public function JabatanIndex()
    {
        $jabatan = Jabatan::all();
        return view('jabatan.jabatan_index', compact('jabatan'));
    }

    public function JabatanStore(Request $request)
    {
        try {
            $request->validate([
                'kode_jabatan' => 'required|unique:jabatan,kode_jabatan|max:255',
                'nama_jabatan' => 'required|max:255',
            ]);

            Jabatan::create($request->all());

            return redirect()->route('admin.jabatan')->with('success', 'Jabatan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return redirect()->route('admin.jabatan')->with('error', 'Jabatan gagal ditambahkan.');
        }
    }

    public function JabatanUpdate(Request $request, $kode_jabatan)
    {
        $kode_jabatan = $request->kode_jabatan;
        // dd($kode_jabatan);
        try {
            $request->validate([
                'nama_jabatan' => 'required|max:255',
            ]);

            $jabatan = Jabatan::findOrFail($kode_jabatan);
            $jabatan->update($request->all());

            return redirect()->back()->with('success', 'Jabatan berhasil diupdate.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Jabatan gagal diupdate.');
        }
    }

    public function JabatanDelete($kode_jabatan)
    {
        try {
            $jabatan = Jabatan::findOrFail($kode_jabatan);
            $jabatan->delete();

            return redirect()->back()->with('success', 'Jabatan berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Jabatan gagal dihapus.');
        }
    }
}
