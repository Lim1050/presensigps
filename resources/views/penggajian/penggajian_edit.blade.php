@extends('layouts.admin.admin_master')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Gaji {{ $penggajian->kode_penggajian }}</h1>
</div>
<div class="card shadow mb-4">

    <div class="card-body">
        <form action="{{ route('admin.penggajian.update', $penggajian->kode_penggajian) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Data Karyawan -->
            <div class="row mt-4">
                <div class="col">
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
                {{-- <h3>Preview Gaji</h3> --}}
                <div id="previewContent"></div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
                    <button type="button" id="previewEditButton" class="btn btn-info mt-3">Preview Edit Gaji</button>
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
    .preview-gaji h3 {
        margin-bottom: 20px;
    }
    .preview-gaji h4 {
        margin-bottom: 15px;
        color: #333;
    }
    .table {
        margin-bottom: 0;
    }
    .thead-light th {
        background-color: #f8f9fa;
    }
    .text-right {
        text-align: right;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>


<script>
    // Ambil jumlah hari kerja dari server
    const jumlahHariKerja = {{ $penggajian->jumlah_hari_kerja }};

    // Ambil elemen input dan elemen display
    const jumlahHariMasukInput = document.getElementById('jumlah_hari_masuk');
    const jumlahHariTidakMasukInput = document.getElementById('jumlah_hari_tidak_masuk');

    // Fungsi untuk memperbarui jumlah hari tidak masuk
    function updateJumlahHariTidakMasuk() {
        const jumlahHariMasuk = parseInt(jumlahHariMasukInput.value) || 0;
        const jumlahHariTidakMasuk = jumlahHariKerja - jumlahHariMasuk;

        // Update jumlah hari tidak masuk
        jumlahHariTidakMasukInput.value = jumlahHariTidakMasuk >= 0 ? jumlahHariTidakMasuk : 0;
    }

    // Tambahkan event listener untuk mendeteksi perubahan pada input jumlah hari masuk
    jumlahHariMasukInput.addEventListener('input', function() {
        // Validasi jika jumlah hari masuk melebihi jumlah hari kerja
        if (parseInt(jumlahHariMasukInput.value) > jumlahHariKerja) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: `Jumlah hari masuk tidak boleh lebih dari ${jumlahHariKerja} hari.`,
                confirmButtonText: 'OK'
            });
            jumlahHariMasukInput.value = jumlahHariKerja; // Reset ke jumlah hari kerja
        }
        updateJumlahHariTidakMasuk(); // Update jumlah hari tidak masuk
    });

    // Inisialisasi jumlah hari tidak masuk saat halaman dimuat
    updateJumlahHariTidakMasuk();
</script>

<script>
    $('#previewEditButton').click(function() {
        // Validasi form
        var nik = $('#nik').val();
        var tanggalGaji = $('#tanggal_gaji').val();
        var kodeCabang = $('#kode_cabang').val();
        var kodeLokasiPenugasan = $('#kode_lokasi_penugasan').val();

        // Debugging: Log nilai field
        console.log('NIK:', nik);
        console.log('Tanggal Gaji:', tanggalGaji);
        console.log('Kode Cabang:', kodeCabang);
        console.log('Kode Lokasi Penugasan:', kodeLokasiPenugasan);

        if (!nik || !tanggalGaji || !kodeCabang || !kodeLokasiPenugasan) {
            alert('Mohon lengkapi semua field yang diperlukan.');
            return;
        }

        var formData = $('#penggajianForm').serialize();

        // Debugging: Cek data yang dikirim
        console.log('Data yang akan dikirim:', formData);

        $.ajax({
            url: "{{ route('admin.penggajian.preview') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                $('#previewContent').html(response);
                $('#previewGaji').show();
            },
            error: function(xhr, status, error) {
                var errorMessage = "Terjadi kesalahan: ";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += error;
                }
                alert(errorMessage);
                console.error(xhr.responseText);
            }
        });
    });
</script>

@endsection
