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
    $('.select2-karyawan').select2({
        placeholder: 'Pilih Karyawan',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.karyawan.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nik + ' - ' + item.nama_lengkap,
                            id: item.nik
                        }
                    })
                };
            },
            cache: true
        }
    });

    // Inisialisasi Select2 untuk Jabatan
    $('.select2-jabatan').select2({
        placeholder: 'Pilih Jabatan',
        allowClear: true
    });

    // Inisialisasi Select2 untuk Lokasi Penugasan
    $('.select2-lokasi_penugasan').select2({
        placeholder: 'Pilih Lokasi Penugasan',
        allowClear: true
    });

    // Inisialisasi Select2 untuk Cabang
    $('.select2-cabang').select2({
        placeholder: 'Pilih Kantor Cabang',
        allowClear: true
    });

    // Event handler saat karyawan dipilih
    $('#nik').on('change', function() {
        var nik = $(this).val();
        if(nik) {
            $.ajax({
                url: '/admin/karyawan/get-data/' + nik,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Set nilai untuk jabatan
                    var jabatanOption = new Option(data.nama_jabatan, data.kode_jabatan, true, true);
                    $('#kode_jabatan').empty().append(jabatanOption).trigger('change');
                    $('#kode_jabatan_hidden').val(data.kode_jabatan);

                    // Set nilai untuk lokasi penugasan
                    var lokasiOption = new Option(data.nama_lokasi_penugasan, data.kode_lokasi_penugasan, true, true);
                    $('#kode_lokasi_penugasan').empty().append(lokasiOption).trigger('change');
                    $('#kode_lokasi_penugasan_hidden').val(data.kode_lokasi_penugasan);

                    // Set nilai untuk cabang
                    var cabangOption = new Option(data.nama_cabang, data.kode_cabang, true, true);
                    $('#kode_cabang').empty().append(cabangOption).trigger('change');
                    $('#kode_cabang_hidden').val(data.kode_cabang);

                    // Disable select fields
                    $('#kode_jabatan, #kode_lokasi_penugasan, #kode_cabang').prop('disabled', true);

                    // Ambil jumlah THR
                    $.ajax({
                        url: '/admin/thr/get-thr/' + nik,
                        type: 'GET',
                        dataType: 'json',
                        success: function(thrData) {
                            if(thrData.success) {
                                $('#jumlah_thr').val(thrData.jumlah_thr);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            // Reset semua field
            $('#kode_jabatan, #kode_lokasi_penugasan, #kode_cabang').val('').trigger('change').prop('disabled', false);
            $('#kode_jabatan_hidden, #kode_lokasi_penugasan_hidden, #kode_cabang_hidden').val('');
            $('#jumlah_thr').val('');
        }
    });

    // Handle form submit
    $('form').on('submit', function(e) {
        // Enable disabled fields before submit
        $('#kode_jabatan, #kode_lokasi_penugasan, #kode_cabang').prop('disabled', false);
    });

    // Tambahkan console.log untuk debugging
    $('#jumlah_thr').on('change', function() {
        console.log('Jumlah THR changed:', $(this).val());
    });

    $('.select2-jabatan').select2({
        placeholder: 'Pilih Jabatan',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.jabatan.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_jabatan,
                            id: item.kode_jabatan
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('.select2-lokasi_penugasan').select2({
        placeholder: 'Pilih Lokasi Penugasan',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.lokasi-penugasan.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_lokasi_penugasan,
                            id: item.kode_lokasi_penugasan
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('.select2-cabang').select2({
        placeholder: 'Pilih Kantor Cabang',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.cabang.search") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_cabang,
                            id: item.kode_cabang
                        }
                    })
                };
            },
            cache: true
        }
    });
});
</script>
@endpush
