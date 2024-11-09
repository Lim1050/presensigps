@extends('layouts.admin.admin_master')
@section('content')

<style>
    .icon-placeholder {
        position: relative;
        /* display: inline-block; */
    }

    .icon-placeholder input {
        padding-left: 30px; /* Adjust padding to make room for the icon */
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
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Penggajian Karyawan</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
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
                <a href="{{ route('admin.penggajian.create') }}" class="btn btn-primary mb-3">Tambah Penggajian</a>
            </div>
        </div>

        <!-- Form Filter -->
        <div class="row mb-3">
            <div class="col-12">
                <form action="{{ route('admin.penggajian') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="bulan" class="form-control">
                                <option value="">Pilih Bulan</option>
                                @foreach($bulanList as $key => $value)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="kode_cabang" class="form-control">
                                <option value="">Pilih Cabang</option>
                                @foreach($cabangList as $kode => $nama)
                                    <option value="{{ $kode }}" {{ request('kode_cabang') == $kode ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Pilih Status</option>
                                @foreach($statusList as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari NIK/Nama" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Penggajian</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Bulan</th>
                                <th class="text-center">Gaji Kotor</th>
                                <th class="text-center">Total Potongan</th>
                                <th class="text-center">Gaji Bersih</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penggajian as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_penggajian }}</td>
                                <td class="text-center">{{ $item->karyawan->nik }}</td>
                                <td class="text-center">{{ $item->karyawan->nama_lengkap }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->bulan)->translatedFormat('F Y') }}</td>
                                <td class="text-center">Rp {{ number_format($item->total_gaji_kotor, 0, ',', '.') }}</td>
                                <td class="text-center">Rp {{ number_format($item->total_potongan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->gajiBersihRupiah }}</td>
                                <td class="text-center">
                                    @if ($item->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif ($item->status == 'disetujui')
                                        <span class="badge badge-primary">Disetujui</span>
                                    @elseif ($item->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif ($item->status == 'dibayar')
                                        <span class="badge badge-success">Dibayar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.penggajian.show', $item->kode_penggajian) }}" class="btn btn-info" title="Lihat"><i class="bi bi-list"></i></a>
                                    <a href="{{ route('admin.penggajian.edit', $item->kode_penggajian) }}" class="btn btn-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{ route('admin.penggajian.delete', $item->kode_penggajian) }}" title="Delete" class="btn btn-danger delete-confirm"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $penggajian->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ... (script yang sudah ada) ...

    $(".delete-confirm").click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Data Ini Akan Dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>


@endpush


@endsection
