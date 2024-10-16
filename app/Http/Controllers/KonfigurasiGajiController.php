<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiGaji;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KonfigurasiGajiController extends Controller
{
    public function KonfigurasiGajiIndex()
    {
        $konfigurasiGaji = KonfigurasiGaji::all();
        return view('konfigurasi.gaji', compact('konfigurasiGaji'));
    }

    public function create()
    {
        return view('konfigurasi_gaji.create');
    }

    public function KonfigurasiGajiStore(Request $request)
    {
        try {
            $request->validate([
                'kode_jenis_gaji' => 'required|string|max:255|unique:konfigurasi_gaji,kode_jenis_gaji',
                'jenis_gaji' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            KonfigurasiGaji::create($request->all());

            return redirect()->back()->with('success', 'Jenis gaji berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('kode_jenis_gaji')) {
                $duplikatKode = $request->kode_jenis_gaji;
                $errorMessage = "Data Gagal Disimpan! Kode Jenis Gaji '$duplikatKode' sudah ada!";
            } else {
                $errorMessage = 'Data Gagal Disimpan! ' . $errors->first();
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Disimpan! Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function KonfigurasiGajiUpdate(Request $request, $kode_jenis_gaji)
    {
        try {
            $konfigurasiGaji = KonfigurasiGaji::findOrFail($kode_jenis_gaji);

            $request->validate([
                'kode_jenis_gaji' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('konfigurasi_gaji', 'kode_jenis_gaji')->ignore($kode_jenis_gaji, 'kode_jenis_gaji'),
                ],
                'jenis_gaji' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // Jika kode_jenis_gaji diubah, kita perlu menanganinya secara khusus
            if ($request->kode_jenis_gaji !== $kode_jenis_gaji) {
                // Hapus record lama
                $konfigurasiGaji->delete();
                // Buat record baru dengan kode_jenis_gaji yang baru
                KonfigurasiGaji::create($request->all());
            } else {
                // Update record yang ada jika kode_jenis_gaji tidak berubah
                $konfigurasiGaji->update($request->except('kode_jenis_gaji'));
            }

            return redirect()->back()->with('success', 'Jenis gaji berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('kode_jenis_gaji')) {
                $duplikatKode = $request->kode_jenis_gaji;
                $errorMessage = "Data Gagal Diperbarui! Kode Jenis Gaji '$duplikatKode' sudah digunakan oleh data lain.";
            } else {
                $errorMessage = 'Data Gagal Diperbarui! ' . $errors->first();
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Diperbarui! Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function KonfigurasiGajiDelete($kode_jenis_gaji)
    {
        $konfigurasiGaji = KonfigurasiGaji::findOrFail($kode_jenis_gaji);
        $konfigurasiGaji->delete();

        return redirect()->back()->with('success', 'Jenis gaji berhasil dihapus.');
    }
}
