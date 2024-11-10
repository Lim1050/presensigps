@extends('layouts.admin.admin_master')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Berikan Lembur Karyawan</h1>
</div>

<!-- Content Row -->
<div class="container">
    {{-- <div class="col-12"> --}}
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
                <form method="POST" action="{{ route('admin.lembur.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6 sm-3">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control select2-cabang @error('kode_cabang') is-invalid @enderror" required>
                                    <option value="">Pilih Kantor Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                    @endforeach
                                </select>
                                @error('kode_cabang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 sm-3">
                            <div class="form-group">
                                <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control select2-lokasi_penugasan @error('kode_lokasi_penugasan') is-invalid @enderror" required>
                                    <option value="">Pilih Lokasi Penugasan</option>
                                </select>
                                @error('kode_lokasi_penugasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 sm-3">
                            <div class="form-group">
                                <select name="nik" id="nik" class="form-control select2-karyawan @error('nik') is-invalid @enderror" required>
                                    <option value="">Pilih Karyawan</option>
                                </select>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 sm-3">
                            <div class="form-group">
                                <input type="text" name="jabatan" id="jabatan" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 sm-3">
                            <div class="form-group">
                                <label for="tanggal_presensi">Tanggal Presensi</label>
                                <input type="date"
                                    name="tanggal_presensi"
                                    id="tanggal_presensi"
                                    class="form-control"
                                    value="{{ old('tanggal_presensi') }}"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4 sm-3">
                            <div class="form-group">
                                <label for="waktu_mulai">Waktu Mulai</label>
                                <input type="time"
                                    name="waktu_mulai"
                                    id="waktu_mulai"
                                    class="form-control"
                                    value="{{ old('waktu_mulai') }}"
                                    step="60">
                            </div>
                        </div>
                        <div class="col-md-4 sm-3">
                            <div class="form-group">
                                <label for="waktu_selesai">Waktu Selesai</label>
                                <input type="time"
                                    name="waktu_selesai"
                                    id="waktu_selesai"
                                    class="form-control"
                                    value="{{ old('waktu_selesai') }}"
                                    step="60">
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
                                    class="form-control @error('catatan_lembur') is-invalid @enderror"
                                    rows="3">{{ old('catatan_lembur') }}</textarea>
                                @error('catatan_lembur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col">
                            <button type="submit" class="btn btn-danger">
                                Berikan Lembur
                            </button>
                            <a href="{{ route('admin.lembur') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {{-- </div> --}}
</div>



@endsection
@push('myscript')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal_presensi');
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuSelesaiInput = document.getElementById('waktu_selesai');

        // Set tanggal dan waktu saat ini
        const now = new Date();
        const today = now.toISOString().split('T')[0];

        // Set minimum date untuk tanggal presensi
        tanggalInput.setAttribute('min', today);

        // Format waktu saat ini untuk input time
        const currentHour = String(now.getHours()).padStart(2, '0');
        const currentMinute = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;

        function updateMinTime() {
            const selectedDate = tanggalInput.value;

            if (selectedDate === today) {
                // Jika tanggal yang dipilih adalah hari ini
                waktuMulaiInput.setAttribute('min', currentTime);

                // Jika waktu yang dipilih lebih awal dari waktu saat ini, reset input
                if (waktuMulaiInput.value && waktuMulaiInput.value < currentTime) {
                    waktuMulaiInput.value = '';
                }
            } else {
                // Jika tanggal yang dipilih adalah hari lain
                waktuMulaiInput.removeAttribute('min');
            }
        }

        // Set nilai awal tanggal ke hari ini
        if (!tanggalInput.value) {
            tanggalInput.value = today;
        }

        // Event listeners
        tanggalInput.addEventListener('change', updateMinTime);
        waktuMulaiInput.addEventListener('change', updateMinTime);

        // Inisialisasi validasi
        updateMinTime();
    });
</script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2-cabang').select2({
        placeholder: 'Pilih Kantor Cabang',
        allowClear: true
    });
    $('.select2-lokasi_penugasan').select2({
        placeholder: 'Pilih Lokasi Penugasan',
        allowClear: true
    });
    $('.select2-karyawan').select2({
        placeholder: 'Pilih Karyawan',
        allowClear: true
    });

    // Event handler saat cabang dipilih
    $('#kode_cabang').on('change', function() {
        var kode_cabang = $(this).val();
        if(kode_cabang) {
            $.ajax({
                url: '/admin/lokasi-penugasan/get-by-cabang/' + kode_cabang,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#kode_lokasi_penugasan').empty().append('<option value="">Pilih Lokasi Penugasan</option>');
                    $.each(data, function(key, value) {
                        $('#kode_lokasi_penugasan').append('<option value="' + value.kode_lokasi_penugasan + '">' + value.nama_lokasi_penugasan + '</option>');
                    });
                    $('#kode_lokasi_penugasan').trigger('change');
                }
            });
        } else {
            $('#kode_lokasi_penugasan').empty().append('<option value="">Pilih Lokasi Penugasan</option>');
            $('#nik').empty().append('<option value="">Pilih Karyawan</option>');
            $('#jabatan').val('');
        }
    });

    // Event handler saat lokasi penugasan dipilih
    $('#kode_lokasi_penugasan').on('change', function() {
        var kode_lokasi_penugasan = $(this).val();
        if(kode_lokasi_penugasan) {
            $.ajax({
                url: '/admin/karyawan/get-by-lokasi/' + kode_lokasi_penugasan,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#nik').empty().append('<option value="">Pilih Karyawan</option>');
                    $.each(data, function(key, value) {
                        $('#nik').append('<option value="' + value.nik + '">' + value.nama_lengkap + '</option>');
                    });
                }
            });
        } else {
            $('#nik').empty().append('<option value="">Pilih Karyawan</option>');
            $('#jabatan').val('');
        }
    });

    // Event handler saat karyawan dipilih
    $('#nik').on('change', function() {
        var nik = $(this).val();
        if(nik) {
            $.ajax({
                url: '/admin/karyawan/get-jabatan/' + nik,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#jabatan').val(data.nama_jabatan);
                }
            });
        } else {
            $('#jabatan').val('');
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

