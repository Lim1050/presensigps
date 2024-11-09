@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit / Cuti</div>
    <div class="right"></div>
</div>
{{-- App Header --}}
@endsection
@section('content')
<div class="row">
    <div class="col" style="margin-top: 70px;">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('warning');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">{{ $messagesuccess }}</div>
        @elseif (Session::get('warning'))
            <div class="alert alert-warning">{{ $messageerror }}</div>
        @endif
    </div>
</div>
<div class="tab-content" style="margin-bottom:100px;">
    <div class="row">
        <div class="col">
            <style>
                .historycontent{
                    display: flex;
                }
                .datapresensi{
                    margin-left: 10px;
                }
                .status {
                    position: absolute;
                    right: 5px;
                    bottom: 20px;
                }
                .fixed-top {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1030; /* z-index tinggi untuk memastikan di atas elemen lainnya */
                    background-color: white; /* Pastikan background-color diatur agar tidak transparan */
                    padding: 10px; /* Tambahkan padding untuk memberikan sedikit ruang */
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Tambahkan shadow untuk efek visual */
                }
            </style>
            <form action="{{ route('izin') }}" method="GET">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="form-control selectmaterialize">
                                <option value="">Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ Request('bulan') == $i ? 'selected' : '' }}>{{ $months[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control selectmaterialize">
                                <option value="">Tahun</option>
                                @php
                                    $tahun_mulai = 2020;
                                    $tahun_sekarang = date("Y");
                                @endphp
                                @for ($tahun=$tahun_mulai; $tahun <= $tahun_sekarang; $tahun++)
                                    <option value="{{ $tahun }}" {{ Request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-danger btn-block"><ion-icon name="search-outline"></ion-icon>Cari</button>
                        </div>
                    </div>
                </div>
            </form>
            {{-- style="position: fixed; width:100%; overflow-y:scroll; height:1000px;" --}}
            <div class="row">
                <div class="col">
                @foreach ($dataIzin as $izin)
                <div class="card mb-1" >
                    <div class="card-body">
                        <div class="historycontent">
                            <div class="iconpresensi card_izin" kode_izin="{{ $izin->kode_izin }}" status_approved="{{ $izin->status_approved }}" data-toggle="modal" data-target="#actionSheetIconed">
                                @if ($izin->status == "izin")
                                <ion-icon style="font-size: 40px;" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                                @elseif ($izin->status == "sakit")
                                <ion-icon style="font-size: 40px;" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="checkmark"></ion-icon>
                                @elseif ($izin->status == "cuti")
                                <ion-icon style="font-size: 40px;" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="checkmark"></ion-icon>
                                @endif
                            </div>

                            <div class="datapresensi">
                                <h3 style="line-height: 3px">{{ date("d-m-Y",strtotime($izin->tanggal_izin_dari)) }} ({{ $izin->status == "izin" ? "Izin" : ($izin->status == "sakit" ? "Sakit" : "Cuti")}} {{ hitunghari($izin->tanggal_izin_dari, $izin->tanggal_izin_sampai) }} Hari)</h3>
                                <small>{{ date("d-m-Y",strtotime($izin->tanggal_izin_dari)) }} s/d {{ date("d-m-Y",strtotime($izin->tanggal_izin_sampai)) }}</small>

                                <h4 >{{ $izin->keterangan }}</h4>

                                    @if ($izin->status == "cuti")
                                    <p class="text-secondary"><ion-icon name="calendar-outline"></ion-icon> {{ $izin->nama_cuti }}</p>
                                    @endif


                                    @if (!empty($izin->surat_sakit))
                                        <a href="#" data-toggle="modal" data-target="#imageDisplayContainer{{ $izin->kode_izin }}" class="text-danger">
                                            <ion-icon name="document-attach-outline"></ion-icon> Lihat Surat Sakit
                                        </a>
                                    @endif
                            </div>

                            <div class="status">
                                <span class="status {{ $izin->status_approved == "1" ? "text-success" : ($izin->status_approved == "2" ? "text-danger" : "text-warning")}}">{{ $izin->status_approved == "1" ? "Diterima" : ($izin->status_approved == "2" ? "Ditolak" : "Menunggu") }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for displaying the image -->
                <div class="modal fade" id="imageDisplayContainer{{ $izin->kode_izin }}" data-backdrop="static" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $izin->kode_izin }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @php
                                    $path = Storage::url("uploads/surat_sakit/" . $izin->surat_sakit);
                                @endphp
                                <img id="imageDisplay" src="{{ $path }}" alt="Image will be displayed here" width="100%" height="auto" />
                            </div>
                            <div class="modal-footer">
                                <div class="btn-inline">
                                    <a href="#" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
    <a href="#" class="fab bg-danger" data-toggle="dropdown">
        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">

        <a href="{{ route('izin.absen') }}" class="dropdown-item bg-danger">
            <ion-icon name="reader-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Izin Absen</p>
        </a>

        <a href="{{ route('izin.sakit') }}" class="dropdown-item bg-danger">
            <ion-icon name="medkit-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
            <p>Izin Sakit</p>
        </a>

        <a href="{{ route('izin.cuti') }}" class="dropdown-item bg-danger">
            <ion-icon name="calendar-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
            <p>Izin Cuti</p>
        </a>
    </div>
</div>


{{-- Modal Pop UP Action --}}
<div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi</h5>
            </div>
            <div class="modal-body" id="showact">

            </div>
        </div>
    </div>
</div>

{{-- Modal Delete --}}
<div class="modal fade dialogbox" id="deleteConfirm" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yakin Dihapus ?</h5>
            </div>
            <div class="modal-body">
                Data Pengajuan Izin Akan dihapus
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                    <a href="" class="btn btn-text-danger" id="hapuspengajuan">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
    <script>
        // function displayImage(imageUrl) {
        //     console.log(imageUrl); // Debugging line
        //     var imgElement = document.getElementById('imageDisplay');
        //     imgElement.src = imageUrl;
        //     imgElement.style.display = 'block';
        // }

        $(function(){
            $(".card_izin").click(function(e){
                var kode_izin = $(this).attr("kode_izin");
                var status_approved = $(this).attr("status_approved");
                if(status_approved == 1) {
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Data Sudah Disetujui! Tidak Dapat Diubah!',
                    icon: 'warning',
                    });
                } else if (status_approved == 2) {
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Data Sudah Ditolak! Tidak Dapat Diubah!',
                    icon: 'warning',
                    });
                } else {
                    $("#showact").load('/izin/' + kode_izin + '/showact');
                }
            });
        });
    </script>
@endpush
