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

    #dari, #sampai > span:hover{
        cursor: pointer;
        }
</style>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Cashbon Limit</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
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
                <h2>Global Limit</h2>
                <form action="{{ route('admin.konfigurasi.cashbon.global.limit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="global_limit ">Global Limit</label>
                        <input type="number" class="form-control" id="global_limit" name="global_limit" value="{{ $globalLimit }}">
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="update_all" id="update_all" value="1">
                        <label class="form-check-label" for="update_all">
                            Update all personal limits to match the new global limit
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger">Update Global Limit</button>
                </form>

                <h2 class="mt-3">Limit Karyawan</h2>
                <div class="table-responsive">
                <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Lengkap</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Lokasi Penugasan</th>
                            <th class="text-center">Kantor Cabang</th>
                            <th class="text-center">Limit</th>
                            <th class="text-center">Terpakai</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($karyawan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->nama_lengkap }}</td>
                            <td class="text-center">{{ $item->jabatan->nama_jabatan }}</td>
                            <td class="text-center">{{ $item->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                            <td class="text-center">{{ $item->cabang->nama_cabang }}</td>
                            <td class="text-right">Rp {{ number_format($item->cashbonKaryawanLimit->limit ?? $globalLimit) }}</td>
                            {{-- {{ $item->cashbonTransactions()->sum('amount') }} --}}
                            <td class="text-right">Rp {{ number_format($item->cashbon->where('status', 'diterima')->sum('jumlah')) }}</td>
                            <td class="text-right">Rp {{ $item->getFormattedAvailableCashbonLimit($globalLimit) }}</td>
                            <td>
                                {{-- {{ route('cashbon.setitemLimit', $item) }} --}}
                                <form action="{{ route('admin.konfigurasi.cashbon.karyawan.limit', $item->nik) }}" method="POST">
                                    @csrf
                                    {{-- {{ $item->cashbonLimit->limit ?? '' }} --}}
                                    <input type="number" name="limit" value="{{ $item->cashbonKaryawanLimit->limit ?? $globalLimit }}">
                                    <button type="submit" class="btn btn-danger">Update Limit</button>
                                </form>
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



@endsection
@push('myscript')
<script>
    let table = new DataTable('#dataTable');
</script>
@endpush
