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
                            <td width="200">Jumlah Hari Masuk</td>
                            <td width="10">:</td>
                            <td>
                                <input type="number" id="jumlah_hari_masuk" name="jumlah_hari_masuk" value="{{ $penggajian->jumlah_hari_masuk }}" class="form-control" max="{{ $penggajian->jumlah_hari_kerja }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah Ketidakhadiran</td>
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
        const jumlahHariMasukInput = document.getElementById('jumlah_hari_masuk');
        const jumlahHariTidakMasukInput = document.getElementById('jumlah_hari_tidak_masuk');

        function updateJumlahHariTidakMasuk() {
            const jumlahHariMasuk = parseInt(jumlahHariMasukInput.value) || 0;
            jumlahHariTidakMasukInput.value = Math.max(0, jumlahHariKerja - jumlahHariMasuk);
        }

        jumlahHariMasukInput.addEventListener('input', function() {
            if (parseInt(jumlahHariMasukInput.value) > jumlahHariKerja) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: `Jumlah hari masuk tidak boleh lebih dari ${jumlahHariKerja} hari.`,
                    confirmButtonText: 'OK'
                });
                jumlahHariMasukInput.value = jumlahHariKerja;
            }
            updateJumlahHariTidakMasuk();
        });

        updateJumlahHariTidakMasuk();

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
