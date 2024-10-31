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
    <h1 class="h3 mb-0 text-gray-800">Lembur Karyawan</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
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
                        <a href="{{ route('admin.lembur.create') }}" class="btn btn-primary mb-3">Tambah Lembur</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">No.</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Lokasi Penugasan</th>
                                    <th class="text-center">Kantor Cabang</th>
                                    <th class="text-center">Tanggal Presensi</th>
                                    <th class="text-center">Waktu Mulai</th>
                                    <th class="text-center">Waktu Selesai</th>
                                    <th class="text-center">Lintas Hari</th>
                                    <th class="text-center">Lembur saat Libur</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lembur as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->nik }}</td>
                                    <td class="text-center">{{ $item->karyawan->nama_lengkap }}</td>
                                    <td class="text-center">{{ $item->karyawan->jabatan->nama_jabatan }}</td>
                                    <td class="text-center">{{ $item->karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                                    <td class="text-center">{{ $item->karyawan->Cabang->nama_cabang }}</td>
                                    <td class="text-center">{{ $item->tanggal_presensi }}</td>
                                    <td class="text-center">{{ $item->waktu_mulai }}</td>
                                    <td class="text-center">{{ $item->waktu_selesai }}</td>
                                    <td class="text-center">
                                        @if ($item->lintas_hari == 1)
                                            <span class="badge badge-success">Ya</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->lembur_libur == 1)
                                            <span class="badge badge-success">Ya</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($item->status == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif ($item->status == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="col mb-2">
                                            <a href="{{ route('admin.lembur.show', $item->id) }}" class="btn-sm btn-info mx-1" title="Lihat"><i class="bi bi-list"></i></a>
                                        </div>
                                        <div class="col mb-2">
                                        <a href="{{ route('admin.lembur.edit', $item->id) }}" class="btn-sm btn-warning mx-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        </div>
                                        <div class="col mb-2">
                                        <form action="{{ route('admin.lembur.delete', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        </div>
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
</div>



@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let table = new DataTable('#dataTable');

    $(document).ready(function() {
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data Lembur ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@endsection
