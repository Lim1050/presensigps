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
    <h1 class="h3 mb-0 text-gray-800">Pengajuan Sakit / Izin</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            {{-- <div class="card-body">
                <div class="form-group">
                    <label for="">Tanggal Presensi</label>
                    <input type="date" class="form-control" id="tanggal_presensi" value="{{ date("Y-m-d") }}" name="tanggal_presensi" placeholder=" Tanggal Presensi">
                </div>
            </div> --}}
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
                                <th>Tanggal Sakit/Izin</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Status Approval</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($sakit_izin as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->tanggal_izin)) }}</td>
                                <td>{{ $item->status == "sakit" ? "Sakit" : "Izin" }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td><span class="badge {{ $item->status_approved == "1" ? "badge-success" : ($item->status_approved == "2" ? "badge-danger" : "badge-warning")}}">{{ $item->status_approved == "1" ? "Disetujui" : ($item->status_approved == "2" ? "Ditolak" : "Pending") }}</span></td>
                                <td>
                                    @if ($item->status_approved == 0)
                                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAproval" data-id="{{ $item->id }}"><i class="bi bi-box-arrow-right"></i> Aksi</a>
                                    @else
                                        <a href="{{ route('admin.batalkan.sakit.izin', $item->id) }}" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i> Batalkan</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aproval -->
<div class="modal fade" id="modalAproval" tabindex="-1" aria-labelledby="modalAprovalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAprovalLabel">Aproval Sakit / Izin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.approval.sakit.izin') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="status_approved" id="status_approved" class="form-control">
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('myscript')
<script>
    $('#modalAproval').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var izinSakitId = button.data('id'); // Ambil nilai dari atribut data-id

        // Masukkan nilai izinSakitId ke dalam input hidden
        var modal = $(this);
        modal.find('.modal-body #id').val(izinSakitId);
    });
</script>
@endpush

@endsection
