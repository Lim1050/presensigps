@extends('layouts.master')
@section('header')
{{-- css datepicker --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }
    .datepicker-date-display {
        background-color: #ca364b !important;
    }
    .datepicker-cancel, .datepicker-clear, .datepicker-today, .datepicker-done {
    color: #ca364b !important;
    }
    .datepicker-table td.is-selected {
    background-color: #ca364b !important;
    color: #fff !important;
    }
    .datepicker-table td.is-today {
    color: #ca364b;
    }
    #keterangan {
        height: 5rem !important;
    }
    .btn-danger {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-danger ion-icon {
        margin-left: 5px; /* Jarak antara teks dan ikon */
        vertical-align: center; /* Memastikan ikon sejajar vertikal dengan teks */
    }
</style>
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('izin') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Pengajuan Izin Cuti </div>
    <div class="right"></div>
</div>
{{-- App Header --}}
@endsection
@section('content')
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <form action="{{ route('izin.cuti.store') }}" method="POST" enctype="multipart/form-data" style="min-height: 1000px" id="formIzinCuti">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control datepicker" name="tanggal_izin_dari" id="tanggal_izin_dari" placeholder="Dari">
            </div>

            <div class="form-group">
                <input type="text" class="form-control datepicker" name="tanggal_izin_sampai" id="tanggal_izin_sampai" placeholder="Sampai">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="jumlah_hari" id="jumlah_hari" placeholder="Jumlah Hari" autocomplete="off" readonly>
            </div>

            <div class="form-group">
                <select name="kode_cuti" id="kode_cuti" class="form-control selectmaterialize">
                    <option value="">Pilih Jenis Cuti</option>
                    @foreach ($master_cuti as $cuti)
                    <option value="{{ $cuti->kode_cuti }}">{{ $cuti->nama_cuti }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan">
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-danger btn-block">
                        Kirim <ion-icon name="send-outline"></ion-icon>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            function loadjumlahhari(){
                var dari = $("#tanggal_izin_dari").val();
                var sampai = $("#tanggal_izin_sampai").val();
                var date1 = new Date(dari);
                var date2 = new Date(sampai);

                // To calculate the time difference of two dates
                var Difference_In_Time = date2.getTime() - date1.getTime();

                // To calculate the no. of days between two dates
                var Diffrence_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                if(dari == "" || sampai == ""){
                    var jumlah_hari = 0;
                } else {
                    var jumlah_hari = Diffrence_In_Days + 1
                }

                // To display the final no. of days (result)
                $("#jumlah_hari").val(jumlah_hari + " Hari");
            }

            $("#tanggal_izin_dari, #tanggal_izin_sampai").change(function(e) {
                loadjumlahhari();
            });

            // $("#tanggal_izin").change(function(e){
            //     var tanggal_izin = $(this).val();
            //     $.ajax({
            //         type:'POST',
            //         url:'/presensi/cek/pengajuan/sakit-izin',
            //         data: {
            //             _token:"{{ csrf_token() }}",
            //             tanggal_izin: tanggal_izin
            //         },
            //         cache: false,
            //         success: function(respond){
            //             if (respond != 0) {
            //                 Swal.fire({
            //                 title: 'Oops!',
            //                 text: 'Anda sudah melakukan pengajuan sakit/izin pada tanggal tersebut!',
            //                 icon: 'warning',
            //                 }).then((result) => {
            //                     $("#tanggal_izin").val("");
            //                 });
            //             }
            //         }
            //     });
            // });

            $("#formIzinCuti").submit(function(){
                var tanggal_izin_dari = $("#tanggal_izin_dari").val();
                var tanggal_izin_sampai = $("#tanggal_izin_sampai").val();
                var jumlah_hari = $("#jumlah_hari").val();
                var kode_cuti = $("#kode_cuti").val();
                var keterangan = $("#keterangan").val();
                if(tanggal_izin_dari==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Awal Tanggal Izin Harus Diisi!',
                    icon: 'warning',
                    });
                    return false;
                } else if (tanggal_izin_sampai=="") {
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Akhir Tanggal Izin Harus Diisi!',
                    icon: 'warning',
                    });
                    return false;
                } else if (jumlah_hari==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Jumlah Hari Harus Diisi!',
                    icon: 'warning',
                    });
                    return false;
                }else if (kode_cuti==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Jenis Cuti Harus Diisi!',
                    icon: 'warning',
                    });
                    return false;
                }else if (keterangan==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan Harus Diisi!',
                    icon: 'warning',
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
