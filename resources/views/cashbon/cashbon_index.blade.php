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
    <h1 class="h3 mb-0 text-gray-800">Persetujuan Cashbon</h1>
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
                <form action="{{ route('admin.cashbon') }}" method="GET">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Tanggal Cashbon</label>
                                <input type="date" class="form-control" id="tanggal_cashbon" value="{{ Request('tanggal_cashbon') }}" name="tanggal_cashbon">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <div class="icon-placeholder">
                                    <i class="bi bi-upc-scan"></i>
                                    <input type="text" class="form-control" id="nik" name="nik" placeholder=" NIK" value="{{ Request('nik') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <div class="icon-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder=" Nama Lengkap" value="{{ Request('nama_lengkap') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <div class="icon-placeholder">
                                    <i class="bi bi-person-vcard"></i>
                                    <input type="text" class="form-control" id="kode_jabatan" name="kode_jabatan" placeholder=" Kode Jabatan" value="{{ Request('kode_jabatan') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <select name="status_pengajuan" id="status_pengajuan" class="form-control">
                                    <option value="">Pilih Status Pengajuan</option>
                                    <option value="1" {{ Request('status_pengajuan') == 1 ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status_pengajuan') == 2 ? 'selected' : '' }}>Ditolak</option>
                                    <option value="0" {{ Request('status_pengajuan') === '0' ? 'selected' : '' }}>Menunggu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <button class="btn btn-danger w-100" type="submit"><i class="bi bi-search"></i> Cari Data</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Kode Cashbon</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jabatan</th>
                                        <th>Tanggal Cashbon</th>
                                        <th>Jumlah Cashbon</th>
                                        <th>Keterangan</th>
                                        <th>Status Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ($cashbon as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_cashbon }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->karyawan->nama_lengkap }}</td>
                                        <td>{{ $item->karyawan->jabatan->nama_jabatan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                        <td>Rp {{ number_format($item->jumlah, 2) }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($item->status == 'diterima')
                                                <span class="badge badge-success">Diterima</span>
                                            @elseif ($item->status == 'ditolak')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Diketahui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.cashbon.show', $item->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                            @if ($item->status == 'pending')
                                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPengajuan" data-id="{{ $item->id }}"><i class="bi bi-box-arrow-right"></i> Aksi</a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalPembatalan" data-id="{{ $item->id }}">
                                                    <i class="bi bi-x-square"></i> Batalkan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        {{-- {{ $sakit_izin->links('vendor.pagination.bootstrap-5') }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Pengajuan -->
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPengajuanLabel">Pengajuan Cashbon {{ $item->karyawan->nama_lengkap }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.cashbon.persetujuan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
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

<!-- Modal untuk konfirmasi pembatalan -->
<div class="modal fade" id="modalPembatalan" tabindex="-1" aria-labelledby="modalPembatalanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPembatalanLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan pengajuan cashbon ini?
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.cashbon.pembatalan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="cancelId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
@push('myscript')
<script>
    $('#modalPengajuan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var cashbon_id = button.data('id'); // Ambil nilai dari atribut data-id

        // Masukkan nilai cashbon_Id ke dalam input hidden
        var modal = $(this);
        modal.find('.modal-body #id').val(cashbon_id);
    });
</script>
<script>
    // Script untuk mengisi ID cashbon ke dalam modal
    $('#modalPembatalan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var id = button.data('id'); // Ambil ID dari data-id
        var modal = $(this);
        modal.find('#cancelId').val(id); // Set ID ke input hidden
    });
</script>
@endpush
