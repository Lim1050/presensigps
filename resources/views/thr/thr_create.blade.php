@extends('layouts.admin.admin_master')
@section('content')
    <div class="container">
        <h1>Tambah THR</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
{{-- {{ route('admin.penggajian.store') }} --}}
        <div class="card-body">
            <form action="{{ route('admin.thr.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            {{-- <label for="kode_cabang">Kantor Cabang</label> --}}
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
                    <input type="hidden" name="kode_cabang_hidden" id="kode_cabang_hidden">

                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            {{-- <label for="kode_lokasi_penugasan">Lokasi Penugasan</label> --}}
                            <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control select2-lokasi_penugasan @error('kode_lokasi_penugasan') is-invalid @enderror" required>
                                <option value="">Pilih Lokasi Penugasan</option>
                                @foreach($lokasiPenugasan as $l)
                                    <option value="{{ $l->kode_lokasi_penugasan }}">{{ $l->nama_lokasi_penugasan }}</option>
                                @endforeach
                            </select>
                            @error('kode_lokasi_penugasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="kode_lokasi_penugasan_hidden" id="kode_lokasi_penugasan_hidden">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            {{-- <label for="nik">Karyawan</label> --}}
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
                            {{-- <label for="kode_jabatan">Jabatan</label> --}}
                            <select name="kode_jabatan" id="kode_jabatan" class="form-control select2-jabatan @error('kode_jabatan') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                            </select>
                            @error('kode_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="kode_jabatan_hidden" id="kode_jabatan_hidden">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_thr">Nama THR</label>
                            <input type="text" name="nama_thr" id="nama_thr" class="form-control @error('nama_thr') is-invalid @enderror" value="{{ old('nama_thr') }}" required>
                            @error('nama_thr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', date('Y')) }}" required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jumlah_thr">Jumlah THR</label>
                            <input type="number" name="jumlah_thr" id="jumlah_thr" class="form-control @error('jumlah_thr') is-invalid @enderror" value="{{ old('jumlah_thr') }}" step="0.01" required>
                            @error('jumlah_thr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_penyerahan">Tanggal Penyerahan</label>
                            <input type="date" name="tanggal_penyerahan" id="tanggal_penyerahan" class="form-control @error('tanggal_penyerahan') is-invalid @enderror" value="{{ old('tanggal_penyerahan') }}" required>
                            @error('tanggal_penyerahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Catatan</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger">Simpan</button>
                    <a href="{{ route('admin.thr') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2-cabang, .select2-lokasi_penugasan, .select2-karyawan, .select2-jabatan').select2({
        allowClear: true
    });

    // Nonaktifkan lokasi penugasan dan karyawan di awal
    $('#kode_lokasi_penugasan, #nik, #kode_jabatan').prop('disabled', true);

    // Event handler saat cabang dipilih
    $('#kode_cabang').on('change', function() {
        var kode_cabang = $(this).val();

        // Reset dan nonaktifkan field selanjutnya
        $('#kode_lokasi_penugasan').val('').empty().prop('disabled', true);
        $('#nik').val('').empty().prop('disabled', true);
        $('#kode_jabatan').val('').empty().prop('disabled', true);
        $('#jumlah_thr').val('');

        if(kode_cabang) {
            // Aktifkan lokasi penugasan
            $('#kode_lokasi_penugasan').prop('disabled', false);

            // Ambil lokasi penugasan berdasarkan cabang
            $.ajax({
                url: '/admin/lokasi-penugasan/get-by-cabang/' + kode_cabang,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#kode_lokasi_penugasan').empty().append('<option value="">Pilih Lokasi Penugasan</option>');
                    $.each(data, function(key, value) {
                        $('#kode_lokasi_penugasan').append(
                            `<option value="${value.kode_lokasi_penugasan}">
                                ${value.nama_lokasi_penugasan}
                            </option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching lokasi penugasan:', error);
                    alert('Gagal memuat lokasi penugasan');
                }
            });
        }
    });

    // Event handler saat lokasi penugasan dipilih
    $('#kode_lokasi_penugasan').on('change', function() {
        var kode_lokasi_penugasan = $(this).val();

        // Reset dan nonaktifkan field selanjutnya
        $('#nik').val('').empty().prop('disabled', true);
        $('#kode_jabatan').val('').empty().prop('disabled', true);
        $('#jumlah_thr').val('');

        if(kode_lokasi_penugasan) {
            // Aktifkan karyawan
            $('#nik').prop('disabled', false);

            // Ambil karyawan berdasarkan lokasi penugasan
            $.ajax({
                url: '/admin/karyawan/get-by-lokasi/' + kode_lokasi_penugasan,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#nik').empty().append('<option value="">Pilih Karyawan</option>');
                    $.each(data, function(key, value) {
                        $('#nik').append(
                            `<option value="${value.nik}">
                                ${value.nik} - ${value.nama_lengkap}
                            </option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching karyawan:', error);
                    alert('Gagal memuat karyawan');
                }
            });
        }
    });

    // Event handler saat karyawan dipilih
    $('#nik').on('change', function() {
        var nik = $(this).val();

        // Reset field selanjutnya
        $('#kode_jabatan').val('').empty().prop('disabled', true);
        $('#jumlah_thr').val('');

        if(nik) {
            // Ambil data jabatan karyawan
            $.ajax({
                url: '/admin/karyawan/get-jabatan/' + nik,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Isi dan aktifkan jabatan
                    $('#kode_jabatan').empty().append(
                        `<option value="${data.kode_jabatan}" selected>
                            ${data.nama_jabatan}
                        </option>`
                    ).val(data.kode_jabatan).prop('disabled', true);

                    // Ambil jumlah THR
                    $.ajax({
                        url: '/admin/thr/get-thr/' + nik,
                        type: 'GET',
                        dataType: 'json',
                        success: function(thrData) {
                            if(thrData.success) {
                                $('#jumlah_thr').val(thrData.jumlah_thr);
                            } else {
                                alert('Tidak dapat menemukan data THR');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching THR:', error);
                            alert('Gagal memuat jumlah THR');
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching jabatan:', error);
                    alert('Gagal memuat jabatan');
                }
            });
        }
    });

    // Tambahkan handler submit untuk memastikan jabatan tersimpan
    $('form').on('submit', function(e) {
        // Nonaktifkan disable pada semua field
        $('#kode_cabang, #kode_lokasi_penugasan, #nik, #kode_jabatan').prop('disabled', false);

        // Tambahkan hidden input untuk jabatan jika belum ada
        if ($('#kode_jabatan_hidden').length === 0 && $('#kode_jabatan').val()) {
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'kode_jabatan')
                .attr('id', 'kode_jabatan_hidden')
                .val($('#kode_jabatan').val())
                .appendTo('form');
        }

        // Pastikan semua field terisi
        var isValid = true;
        var errorMessage = 'Harap lengkapi semua field yang diperlukan:';

        if (!$('#kode_cabang').val()) {
            isValid = false;
            errorMessage += '\n- Cabang';
        }
        if (!$('#kode_lokasi_penugasan').val()) {
            isValid = false;
            errorMessage += '\n- Lokasi Penugasan';
        }
        if (!$('#nik').val()) {
            isValid = false;
            errorMessage += '\n- Karyawan';
        }
        if (!$('#kode_jabatan').val()) {
            isValid = false;
            errorMessage += '\n- Jabatan';
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
    });
});
</script>
@endpush
