@extends('layouts.admin.admin_master')
@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Jam Kerja</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="{{ route('admin.konfigurasi.jam-kerja-dept')}}" class="btn btn-danger mb-2"><i class="bi bi-backspace"></i> Kembali</a>
        <form action="{{ route('admin.konfigurasi.jam-kerja-dept.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $item)
                                        <option value="{{ $item->kode_cabang }}">{{ strtoupper($item->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                        <option value="{{ $item->kode_departemen }}">{{ strtoupper($item->nama_departemen) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <tr>
                                    <td>
                                        {{ $hari }}
                                        <input type="hidden" name="hari[]" value="{{ $hari }}">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-control jam_kerja_select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->kode_jam_kerja }} - {{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-danger w-100" type="submit"><i class="bi bi-box-arrow-up"></i> Simpan</button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-center" id="masterJamKerjaTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th colspan="6">Master Jam Kerja</th>
                                </tr>
                                <tr>
                                    <th>Kode Jam Kerja</th>
                                    <th>Nama Jam Kerja</th>
                                    <th>Awal Jam Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Akhir Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                            </thead>
                            <tbody id="masterJamKerjaBody">
                            <!-- Isi tabel akan diperbarui secara dinamis -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@push('myscript')
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
    document.addEventListener('DOMContentLoaded', function () {
        const kodeCabangSelect = document.getElementById('kode_cabang');
        const jamKerjaSelects = document.querySelectorAll('.jam_kerja_select');

        kodeCabangSelect.addEventListener('change', function () {
            const selectedCabang = this.value;

            jamKerjaSelects.forEach(select => {
                // Ambil semua option dalam select
                const options = select.querySelectorAll('option');

                // Tampilkan semua option
                options.forEach(option => {
                    // Jika tidak ada cabang yang dipilih, tampilkan semua
                    if (selectedCabang === "") {
                        option.style.display = 'block';
                    } else {
                        // Tampilkan hanya opsi yang sesuai dengan cabang yang dipilih
                        if (option.dataset.cabang === selectedCabang) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                });

                // Set opsi yang dipilih ke kosong jika tidak ada yang sesuai
                select.value = "";
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const kodeCabangSelect = $('#kode_cabang');
        const jamKerjaSelects = $('.jam_kerja_select');
        const masterJamKerjaBody = $('#masterJamKerjaBody');

        // Fungsi untuk memperbarui dropdown jam kerja
        function updateJamKerjaDropdowns(selectedCabang) {
            jamKerjaSelects.each(function() {
                const select = $(this);
                const options = select.find('option');

                options.each(function() {
                    const option = $(this);

                    // Sembunyikan/tampilkan opsi berdasarkan cabang
                    if (selectedCabang === "" || option.data('cabang') === selectedCabang) {
                        option.show();
                    } else {
                        option.hide();
                    }
                });

                // Reset pilihan
                select.val("");
            });
        }

        // Fungsi untuk memperbarui tabel master jam kerja
        function updateMasterJamKerjaTable(selectedCabang) {
            // Kosongkan tbody sebelum mengisi data baru
            masterJamKerjaBody.empty();

            // Jika tidak ada cabang yang dipilih, tampilkan pesan
            if (!selectedCabang) {
                masterJamKerjaBody.html(`
                    <tr>
                        <td colspan="6" class="text-center">Pilih Cabang Terlebih Dahulu</td>
                    </tr>
                `);
                return;
            }

            // Kirim permintaan AJAX untuk mendapatkan jam kerja
            $.ajax({
                url: `/jam-kerja/${selectedCabang}`, // Sesuaikan dengan route Anda
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Jika tidak ada data
                    if (response.length === 0) {
                        masterJamKerjaBody.html(`
                            <tr>
                                <td colspan="6" class="text-center">Tidak Ada Data Jam Kerja untuk Cabang Ini</td>
                            </tr>
                        `);
                        return;
                    }

                    // Isi tabel dengan data yang diterima
                    response.forEach(function(item) {
                        const row = `
                            <tr>
                                <td class="text-center">${item.kode_jam_kerja}</td>
                                <td class="text-center">${item.nama_jam_kerja}</td>
                                <td class="text-center">${item.awal_jam_masuk}</td>
                                <td class="text-center">${item.jam_masuk}</td>
                                <td class="text-center">${item.akhir_jam_masuk}</td>
                                <td class="text-center">${item.jam_pulang}</td>
                            </tr>
                        `;
                        masterJamKerjaBody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching jam kerja:", error);
                    masterJamKerjaBody.html(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">Gagal Memuat Data</td>
                        </tr>
                    `);
                }
            });
        }

        // Event listener untuk perubahan cabang
        kodeCabangSelect.on('change', function() {
            const selectedCabang = $(this).val();

            // Perbarui dropdown jam kerja
            updateJamKerjaDropdowns(selectedCabang);

            // Perbarui tabel master jam kerja
            updateMasterJamKerjaTable(selectedCabang);
        });

        // Inisialisasi awal
        updateJamKerjaDropdowns("");
        updateMasterJamKerjaTable("");
    });
</script>
@endpush
