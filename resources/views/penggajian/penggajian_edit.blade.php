@extends('layouts.admin.admin_master')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Gaji {{ $penggajian->kode_penggajian }}</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form id="penggajianForm" action="{{ route('admin.penggajian.update', $penggajian->kode_penggajian) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Data Karyawan -->
            <div class="row mt-4">
                <div class="col">
                    <input type="hidden" name="kode_penggajian" value="{{ $penggajian->kode_penggajian }}">
                    <input type="hidden" name="nik" value="{{ $penggajian->karyawan->nik }}">
                    <h4>Data Karyawan</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="200">NIK</td>
                            <td width="10">:</td>
                            <td>{{ $penggajian->karyawan->nik }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $penggajian->karyawan->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $penggajian->karyawan->jabatan->nama_jabatan }}</td>
                        </tr>
                        <tr>
                            <td>Kantor Cabang</td>
                            <td>:</td>
                            <td>{{ $penggajian->cabang->nama_cabang }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi Penugasan</td>
                            <td>:</td>
                            <td>{{ $penggajian->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Hari Kerja</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_hari_kerja }} hari</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Data Kehadiran -->
            <div class="row mt-4">
                <div class="col">
                    <h4>Data Kehadiran</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="200">Jumlah Hadir</td>
                            <td width="10">:</td>
                            <td>
                                <input type="number" id="kehadiran_murni" name="kehadiran_murni" value="{{ $penggajian->kehadiran_murni }}" class="form-control" min="0" max="{{ $penggajian->jumlah_hari_kerja }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td width="200">Jumlah Izin</td>
                            <td width="10">:</td>
                            <td>
                                <input type="number" id="jumlah_izin" name="jumlah_izin" value="{{ $penggajian->jumlah_izin }}" class="form-control" min="0" max="{{ $penggajian->jumlah_hari_kerja }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td width="200">Jumlah Sakit</td>
                            <td width="10">:</td>
                            <td>
                                <input type="number" id="jumlah_sakit" name="jumlah_sakit" value="{{ $penggajian->jumlah_sakit }}" class="form-control" min="0" max="{{ $penggajian->jumlah_hari_kerja }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td width="200">Jumlah Cuti</td>
                            <td width="10">:</td>
                            <td>
                                <input type="number" id="jumlah_cuti" name="jumlah_cuti" value="{{ $penggajian->jumlah_cuti }}" class="form-control" min="0" max="{{ $penggajian->jumlah_hari_kerja }}" required>
                            </td>
                        </tr>

                        <tr>
                            <td>Jumlah Kehadiran</td>
                            <td>:</td>
                            <td>
                                <input type="number" id="jumlah_hari_masuk" name="jumlah_hari_masuk" value="{{ $penggajian->jumlah_hari_masuk }}" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah Tidak Hadir Dengan Keterangan (ISC)</td>
                            <td>:</td>
                            <td>
                                <input type="number" id="jumlah_isc" name="jumlah_isc" value="{{ $penggajian->jumlah_isc }}" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah Tidak Hadir Tanpa Keterangan</td>
                            <td>:</td>
                            <td>
                                <input type="number" id="jumlah_hari_tidak_masuk" name="jumlah_hari_tidak_masuk" value="{{ $penggajian->jumlah_hari_tidak_masuk }}" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Jam Lembur</td>
                            <td>:</td>
                            <td>
                                <input type="number" id="total_jam_lembur" name="total_jam_lembur" value="{{ $penggajian->total_jam_lembur }}" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Gaji</td>
                            <td>:</td>
                            <td>
                                <input type="date" name="tanggal_gaji" value="{{ $penggajian->tanggal_gaji }}" class="form-control" required>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="previewEditGaji" style="display: none;">
                <div id="previewContent"></div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status', $penggajian->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="disetujui" {{ old('status', $penggajian->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ old('status', $penggajian->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dibayar" {{ old('status', $penggajian->status) == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $penggajian->catatan }}</textarea>
            </div>

            <div class="form-group">
                <label for="catatan_perubahan">Catatan Perubahan</label>
                <textarea class="form-control" id="catatan_perubahan" name="catatan_perubahan" rows="3" required>{{ $penggajian->alasan_perubahan }}</textarea>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
                    <button type="button" id="previewEditButton" class="btn btn-info">Preview Edit Gaji</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .preview-gaji {
        padding: 20px;
    }
    .table {
        margin-bottom: 0;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahHariKerja = {{ $penggajian->jumlah_hari_kerja }};
        const kehadiranMurniInput = document.getElementById('kehadiran_murni');
        const jumlahIzinInput = document.getElementById('jumlah_izin');
        const jumlahSakitInput = document.getElementById('jumlah_sakit');
        const jumlahCutiInput = document.getElementById('jumlah_cuti');
        const jumlahHariMasukInput = document.getElementById('jumlah_hari_masuk');
        const jumlahIscInput = document.getElementById('jumlah_isc');
        const jumlahHariTidakMasukInput = document.getElementById('jumlah_hari_tidak_masuk');

        function showValidationError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Input',
                text: message,
                confirmButtonText: 'OK'
            });
        }

        function validateAndAdjustInputs() {
            // Ambil nilai input
            const kehadiranMurni = parseInt(kehadiranMurniInput.value) || 0;
            const jumlahIzin = parseInt(jumlahIzinInput.value) || 0;
            const jumlahSakit = parseInt(jumlahSakitInput.value) || 0;
            const jumlahCuti = parseInt(jumlahCutiInput.value) || 0;

            // Validasi input negatif
            const inputs = [
                { input: kehadiranMurniInput, name: 'Kehadiran Murni' },
                { input: jumlahIzinInput, name: 'Jumlah Izin' },
                { input: jumlahSakitInput, name: 'Jumlah Sakit' },
                { input: jumlahCutiInput, name: 'Jumlah Cuti' }
            ];

            // Cek input negatif
            for (let { input, name } of inputs) {
                const value = parseInt(input.value) || 0;
                if (value < 0) {
                    showValidationError(`${name} tidak boleh kurang dari 0`);
                    input.value = 0;
                    return false;
                }
            }

            // Jika kehadiran murni penuh
            if (kehadiranMurni === jumlahHariKerja) {
                if (jumlahIzin > 0 || jumlahSakit > 0 || jumlahCuti > 0) {
                    showValidationError('Jika kehadiran murni penuh, izin/sakit/cuti harus 0');
                    jumlahIzinInput.value = 0;
                    jumlahSakitInput.value = 0;
                    jumlahCutiInput.value = 0;
                    return false;
                }
            }

            // Hitung total hari
            const totalHari = kehadiranMurni + jumlahIzin + jumlahSakit + jumlahCuti;

            // Validasi total hari
            if (totalHari > jumlahHariKerja) {
                showValidationError(`Total hari ${totalHari}, tidak boleh melebihi ${jumlahHariKerja} hari`);

                // Kurangi input tambahan
                const selisih = totalHari - jumlahHariKerja;

                // Prioritas pengurangan: Cuti -> Sakit -> Izin
                if (jumlahCuti >= selisih) {
                    jumlahCutiInput.value = jumlahCuti - selisih;
                } else if (jumlahCuti > 0) {
                    const sisaSelisih = selisih - jumlahCuti;
                    jumlahCutiInput.value = 0;

                    if (jumlahSakit >= sisaSelisih) {
                        jumlahSakitInput.value = jumlahSakit - sisaSelisih;
                    } else if (jumlahSakit > 0) {
                        const sisaSelisihLagi = sisaSelisih - jumlahSakit;
                        jumlahSakitInput.value = 0;

                        if (jumlahIzin >= sisaSelisihLagi) {
                            jumlahIzinInput.value = jumlahIzin - sisaSelisihLagi;
                        } else {
                            jumlahIzinInput.value = 0;
                        }
                    }
                } else if (jumlahSakit >= selisih) {
                    jumlahSakitInput.value = jumlahSakit - selisih;
                } else if (jumlahSakit > 0) {
                    const sisaSelisih = selisih - jumlahSakit;
                    jumlahSakitInput.value = 0;

                    if (jumlahIzin >= sisaSelisih) {
                        jumlahIzinInput.value = jumlahIzin - sisaSelisih;
                    } else {
                        jumlahIzinInput.value = 0;
                    }
                } else if (jumlahIzin >= selisih) {
                    jumlahIzinInput.value = jumlahIzin - selisih;
                } else {
                    jumlahIzinInput.value = 0;
                }

                return false;
            }

            // Jika total hari kurang
            // if (totalHari < jumlahHariKerja) {
            //     showValidationError(`Total hari (${totalHari}) kurang dari ${jumlahHariKerja} hari`);
            //     return false;
            // }

            return true;
        }

        function updateCalculations() {
            // Validasi input terlebih dahulu
            if (!validateAndAdjustInputs()) {
                return;
            }

            // Konversi input ke integer
            const kehadiranMurni = parseInt(kehadiranMurniInput.value) || 0;
            const jumlahIzin = parseInt(jumlahIzinInput.value) || 0;
            const jumlahSakit = parseInt(jumlahSakitInput.value) || 0;
            const jumlahCuti = parseInt(jumlahCutiInput.value) || 0;

            // Hitung jumlah hari masuk
            const jumlahHariMasuk = kehadiranMurni + jumlahIzin + jumlahSakit + jumlahCuti;
            jumlahHariMasukInput.value = jumlahHariMasuk;

            // Hitung jumlah ISC (izin + sakit + cuti)
            const jumlahIsc = jumlahIzin + jumlahSakit + jumlahCuti;
            jumlahIscInput.value = jumlahIsc;

            // Hitung jumlah hari tidak masuk
            const jumlahHariTidakMasuk = jumlahHariKerja - jumlahHariMasuk;
            jumlahHariTidakMasukInput.value = jumlahHariTidakMasuk;
        }

        // Event listener untuk setiap input
        [kehadiranMurniInput, jumlahIzinInput, jumlahSakitInput, jumlahCutiInput].forEach(input => {
            input.addEventListener('input', updateCalculations);

            input.addEventListener('blur', () => {
                updateCalculations();
            });
        });

        // Inisialisasi perhitungan saat halaman dimuat
        updateCalculations();

        $('#previewEditButton').click(function() {
            const formData = $('#penggajianForm').serialize();
            $.ajax({
                url: "{{ route('admin.penggajian.edit-preview') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#previewContent').html(response);
                    $('#previewEditGaji').show();
                },
                error: function(xhr) {
                    let errorMessage;

                    // Memeriksa apakah pesan kesalahan berisi "Undefined array key 'L'"
                    if (xhr.responseJSON?.message.includes('Undefined array key "L"')) {
                        errorMessage = "Karyawan ini tidak memiliki gaji lembur.";
                    } else {
                        errorMessage = xhr.responseJSON?.message || "Terjadi kesalahan.";
                    }
                    // Menampilkan SweetAlert untuk kesalahan
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-popup',
                            title: 'custom-title',
                            content: 'custom-content',
                            confirmButton: 'custom-confirm-button'
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
