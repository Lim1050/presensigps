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
        <div class="card shadow">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Tanggal Presensi</label>
                    <input type="date" class="form-control" id="tanggal_presensi" value="{{ date("Y-m-d") }}" name="tanggal_presensi" placeholder=" Tanggal Presensi">
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover text-center">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Departemen</th>
                                    <th>Jadwal</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Foto Pulang</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Lokasi</th>
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
    </div>
</div>

<!-- Modal peta lokasi -->
<div class="modal fade" id="modalShowMap" tabindex="-1" aria-labelledby="modalShowMapLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowMapLabel">Lokasi Presensi Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="loadmap">
                <!-- Map content here -->
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    function loadpresensi(){
        var tanggal_presensi = $("#tanggal_presensi").val();
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
    }
    $("#tanggal_presensi").change(function(e) {
        loadpresensi()
    });

    loadpresensi()
});
</script>
@endpush

@endsection
