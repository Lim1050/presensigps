<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiPotongan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KonfigurasiPotonganController extends Controller
{
    public function KonfigurasiPotonganIndex()
    {
        $konfigurasiPotongan = KonfigurasiPotongan::all();
        return view('konfigurasi.potongan', compact('konfigurasiPotongan'));
    }

    public function create()
    {
        return view('konfigurasi_potongan.create');
    }

    public function KonfigurasiPotonganStore(Request $request)
    {
        try {
            $request->validate([
                'kode_jenis_potongan' => 'required|string|max:255|unique:konfigurasi_potongan,kode_jenis_potongan',
                'jenis_potongan' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            KonfigurasiPotongan::create($request->all());

            return redirect()->back()->with('success', 'Jenis Potongan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('kode_jenis_potongan')) {
                $duplikatKode = $request->kode_jenis_Potongan;
                $errorMessage = "Data Gagal Disimpan! Kode Jenis Potongan '$duplikatKode' sudah ada!";
            } else {
                $errorMessage = 'Data Gagal Disimpan! ' . $errors->first();
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Disimpan! Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function KonfigurasiPotonganUpdate(Request $request, $kode_jenis_potongan)
    {
        try {
            $konfigurasiPotongan = KonfigurasiPotongan::findOrFail($kode_jenis_potongan);

            $request->validate([
                'kode_jenis_potongan' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('konfigurasi_potongan', 'kode_jenis_potongan')->ignore($kode_jenis_potongan, 'kode_jenis_potongan'),
                ],
                'jenis_potongan' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // Jika kode_jenis_Potongan diubah, kita perlu menanganinya secara khusus
            if ($request->kode_jenis_potongan !== $kode_jenis_potongan) {
                // Hapus record lama
                $konfigurasiPotongan->delete();
                // Buat record baru dengan kode_jenis_Potongan yang baru
                KonfigurasiPotongan::create($request->all());
            } else {
                // Update record yang ada jika kode_jenis_Potongan tidak berubah
                $konfigurasiPotongan->update($request->except('kode_jenis_potongan'));
            }

            return redirect()->back()->with('success', 'Jenis Potongan berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('kode_jenis_potongan')) {
                $duplikatKode = $request->kode_jenis_potongan;
                $errorMessage = "Data Gagal Diperbarui! Kode Jenis Potongan '$duplikatKode' sudah digunakan oleh data lain.";
            } else {
                $errorMessage = 'Data Gagal Diperbarui! ' . $errors->first();
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Diperbarui! Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function KonfigurasiPotonganDelete($kode_jenis_potongan)
    {
        $konfigurasiPotongan = KonfigurasiPotongan::findOrFail($kode_jenis_potongan);
        $konfigurasiPotongan->delete();

        return redirect()->back()->with('success', 'Jenis Potongan berhasil dihapus.');
    }
}
