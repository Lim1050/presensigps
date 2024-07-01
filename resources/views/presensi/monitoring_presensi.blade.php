@extends('layouts.admin.admin_master')
@section('content')
<style>
    .icon-placeholder {
        position: relative;
        /* display: inline-block; */
    }

    .icon-placeholder input {
        padding-left: 25px; /* Adjust padding to make room for the icon */
    }

    .icon-placeholder .bi {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        /* color: #ccc; Icon color */
    }

    .preview-container {
            margin-top: 20px;
    }
    .preview-container img {
        width: 100px;
        height: 150px;
        object-fit: cover;
        display: none; /* Initially hide the image */
    }
    label{
        margin-left: 20px;
    }
    #tanggal_presensi{
        width:180px;
        margin: 0 20px 20px 20px;
    }
    #tanggal_presensi > span:hover{
        cursor: pointer;
        }
</style>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Monitoring Presensi</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Tanggal Presensi</label>
                    <input type="date" class="form-control" id="tanggal_presensi" name="tanggal_presensi" placeholder=" Tanggal Presensi">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                {{-- <th>Jabatan</th> --}}
                                <th>Departemen</th>
                                <th>Jam Masuk</th>
                                <th>Foto Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Foto Pulang</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="loadpresensi">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $("#tanggal_presensi").change(function(e) {
        var tanggal_presensi = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/admin/monitoring/getpresensi',
            data: {
                _token: "{{ csrf_token() }}",
                tanggal_presensi: tanggal_presensi,
            },
            cache: false,
            success: function(respond) {
                $("#loadpresensi").html(respond);
            },
            error: function(xhr, status, error) {
                console.error('Error loading presensi:', error);
            }
        });
    });
});
</script>
@endpush

@endsection
