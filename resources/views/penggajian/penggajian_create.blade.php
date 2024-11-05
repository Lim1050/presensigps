@extends('layouts.admin.admin_master')
@section('content')
    {{-- <div class="container"> --}}
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Penggajian</h1>
</div>
<div class="card shadow">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form id="penggajianForm" action="{{ route('admin.penggajian.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="kode_cabang">Kantor Cabang</label>
                <select name="kode_cabang" id="kode_cabang" class="form-control">
                    <option value="">Pilih Kantor Cabang</option>
                    @foreach($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="kode_lokasi_penugasan">Lokasi Penugasan</label>
                <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control">
                    <option value="">Pilih Lokasi Penugasan</option>
                    @foreach($lokasi_penugasan as $lp)
                        <option value="{{ $lp->kode_lokasi_penugasan }}">{{ $lp->nama_lokasi_penugasan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nik">NIK Karyawan</label>
                <select name="nik" id="nik" class="form-control">
                    <option value="">Pilih Karyawan</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <input type="text" name="jabatan" id="jabatan" class="form-control" readonly>
            </div>

            <div>
                <label for="tanggal_gaji">Tanggal Gaji</label>
                <input type="date" name="tanggal_gaji" id="tanggal_gaji" class="form-control" value="{{ old('tanggal_gaji') }}">
            </div>

            <div id="previewGaji" style="display: none;">
                <h3>Preview Gaji</h3>
                <div id="previewContent"></div>
            </div>

            <button type="button" id="previewButton" class="btn btn-info mt-3">Preview Gaji</button>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            {{-- {{ route('penggajian.index') }} --}}
            <a href="{{ route('admin.penggajian') }}" class="btn btn-secondary mt-3">Batal</a>
        </form>


    </div>
</div>
@endsection
@push('myscript')
<script>
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
                        $('#nik').append('<option value="' + value.nik + '">' + value.nik + ' - ' + value.nama_lengkap + '</option>');
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
</script>

<script>
    $('#previewButton').click(function() {
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
@endpush
