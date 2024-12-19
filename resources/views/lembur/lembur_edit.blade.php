@extends('layouts.admin.admin_master')
@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Lembur Karyawan</h1>
</div>

<!-- Content Row -->
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            {{-- @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif --}}
            <form method="POST" action="{{ route('admin.lembur.update', $lembur->kode_lembur) }}">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="kode_cabang">Kantor Cabang</label>
                            <input type="text" class="form-control" value="{{ $lembur->karyawan->cabang->nama_cabang }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="kode_lokasi_penugasan">Lokasi Penugasan</label>
                            <input type="text" class="form-control" value="{{ $lembur->karyawan->lokasiPenugasan->nama_lokasi_penugasan }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="nik">Karyawan</label>
                            <input type="text" class="form-control" value="{{ $lembur->karyawan->nama_lengkap }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ $lembur->karyawan->jabatan->nama_jabatan }}" readonly>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="nik" id="nik" value="{{ $lembur->nik }}">
                <div class="row mb-3">
                    <div class="col-md-4 sm-3">
                        <div class="form-group">
                            <label for="tanggal_presensi">Tanggal Presensi</label>
                            <input type="date"
                                name="tanggal_presensi"
                                id="tanggal_presensi"
                                class="form-control"
                                value="{{ old('tanggal_presensi', $lembur->tanggal_presensi) }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-4 sm-3">
                        <div class="form-group">
                            <label for="waktu_mulai">Waktu Mulai</label>
                            <input type="time"
                                name="waktu_mulai"
                                id="waktu_mulai"
                                class="form-control"
                                value="{{ old('waktu_mulai', \Carbon\Carbon::parse($lembur->waktu_mulai)->format('H:i')) }}"
                                step="60"
                                required>
                        </div>
                    </div>
                    <div class="col-md-4 sm-3">
                        <div class="form-group">
                            <label for="waktu_selesai">Waktu Selesai</label>
                            <input type="time"
                                name="waktu_selesai"
                                id="waktu_selesai"
                                class="form-control"
                                value="{{ old('waktu_selesai', \Carbon\Carbon::parse($lembur->waktu_selesai)->format('H:i')) }}"
                                step="60"
                                required>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan_lembur">Catatan Lembur</label>
                            <textarea
                                name="catatan_lembur"
                                id="catatan_lembur"
                                class="form-control"
                                rows="3">{{ old('catatan_lembur', $lembur->catatan_lembur) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col">
                        <button type="submit" class="btn btn-danger">
                            Update Lembur
                        </button>
                        <a href="{{ route('admin.lembur') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal_presensi');
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuSelesaiInput = document.getElementById('waktu_selesai');

        // Set tanggal dan waktu saat ini
        const now = new Date();
        const today = now.toISOString().split('T')[0];
        const originalTanggal = "{{ $lembur->tanggal_presensi }}";
        const originalStatus = "{{ $lembur->status }}";

        // Format waktu saat ini untuk input time
        const currentHour = String(now.getHours()).padStart(2, '0');
        const currentMinute = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;

        // function updateMinTime() {
        //     const selectedDate = tanggalInput.value;

        //     // Jika tanggal yang dipilih adalah hari ini
        //     if (selectedDate === today) {
        //         waktuMulaiInput.setAttribute('min', currentTime);

        //         // Jika waktu yang dipilih lebih awal dari waktu saat ini, reset input
        //         if (waktuMulaiInput.value && waktuMulaiInput.value < currentTime) {
        //             waktuMulaiInput.value = '';
        //         }
        //     } else {
        //         // Jika tanggal yang dipilih adalah hari lain
        //         waktuMulaiInput.removeAttribute('min');
        //     }
        // }

        // Set minimum date untuk tanggal presensi
        tanggalInput.setAttribute('min', today);

        // Event listeners
        tanggalInput.addEventListener('change', updateMinTime);
        waktuMulaiInput.addEventListener('change', updateMinTime);

        // Form submit validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            // Validasi status lembur
            if (originalStatus !== 'pending') {
                event.preventDefault();
                alert('Hanya lembur dengan status pending yang dapat diubah');
                return;
            }

            // Tambahan validasi untuk mencegah perubahan ke tanggal yang sudah lewat
            const selectedDate = new Date(tanggalInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset waktu untuk perbandingan tanggal saja

            if (selectedDate < today) {
                event.preventDefault();
                alert('Tidak dapat mengubah lembur ke tanggal yang sudah lewat');
            }
        });

        // Inisialisasi validasi
        updateMinTime();
    });
</script>

<script>
$(document).ready(function() {
    // Fungsi untuk mengambil jadwal jam kerja
    function fetchJadwalKerja() {
        var tanggal = $('#tanggal_presensi').val();
        var nik = $('#nik').val();

        // Pastikan keduanya terisi
        if (tanggal && nik) {
            $.ajax({
                url: "{{ route('admin.lembur.get.jam.kerja') }}",
                method: 'GET',
                data: {
                    tanggal: tanggal,
                    nik: nik
                },
                success: function(response) {
                    if (response.success) {
                        // Tampilkan informasi jam kerja
                        $('#jadwal_jam_kerja').remove(); // Hapus jadwal sebelumnya jika ada

                        var jadwalHtml = `
                            <div id="jadwal_jam_kerja" class="alert alert-info mt-2">
                                <strong>Jadwal Jam Kerja:</strong><br>
                                Kode Jam Kerja: ${response.kode_jam_kerja}<br>
                                Jam Masuk: ${response.jam_masuk}<br>
                                Jam Pulang: ${response.jam_pulang}
                                ${response.lintas_hari == '1' ? ' (Lintas Hari)' : ''}
                            </div>
                        `;

                        $('#tanggal_presensi').after(jadwalHtml);
                    } else {
                        // Tampilkan pesan jika tidak ditemukan jadwal
                        $('#jadwal_jam_kerja').remove();
                        $('#tanggal_presensi').after(`
                            <div id="jadwal_jam_kerja" class="alert alert-warning mt-2">
                                ${response.message}
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#jadwal_jam_kerja').remove();
                    $('#tanggal_presensi').after(`
                        <div id="jadwal_jam_kerja" class="alert alert-danger mt-2">
                            Terjadi kesalahan saat mengambil jadwal jam kerja.
                        </div>
                    `);
                }
            });
        }
    }

    // Panggil fungsi saat halaman pertama kali dimuat
    fetchJadwalKerja();

    // Tambahkan event listener untuk perubahan tanggal
    $('#tanggal_presensi').on('change', function() {
        fetchJadwalKerja();
    });
});
</script>

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
@endpush
