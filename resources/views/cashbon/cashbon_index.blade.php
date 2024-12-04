@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('keuangan.cashbon-karyawan'))
<style>
    .icon-placeholder {
        position: relative;
    }

    .icon-placeholder input {
        padding-left: 30px; /* Adjust padding to make room for the icon */
    }

    .icon-placeholder .bi {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
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

    #dari, #sampai > span:hover {
        cursor: pointer;
    }
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    @if (Auth::user()->role == 'admin-cabang')
    <h1 class="h3 mb-0 text-gray-800">Persetujuan Cashbon Cabang {{ $cabang->nama_cabang }}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Persetujuan Cashbon</h1>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.cashbon') }}" method="GET">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="tanggal_dari">Tanggal Dari</label>
                                <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari" value="{{ Request('tanggal_dari') }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="tanggal_sampai">Tanggal Sampai</label>
                                <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai" value="{{ Request('tanggal_sampai') }}">
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
                                <select name="kode_jabatan" id="kode_jabatan" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatan as $j)
                                        <option value="{{ $j->kode_jabatan }}" {{ Request('kode_jabatan') == $j->kode_jabatan ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Pilih Status Pengajuan</option>
                                    <option value="diterima" {{ Request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ Request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <button class=" btn btn-danger w-100" type="submit"><i class="bi bi-search"></i> Cari Data</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Kode Cashbon</th>
                                        <th class="text-center">NIK</th>
                                        <th class="text-center">Nama Karyawan</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Cabang</th>
                                        <th class="text-center">Tanggal Cashbon</th>
                                        <th class="text-center">Jumlah Cashbon</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Status Pengajuan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashbon as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->kode_cashbon }}</td>
                                        <td class="text-center">{{ $item->nik }}</td>
                                        <td class="text-center">{{ $item->karyawan->nama_lengkap }}</td>
                                        <td class="text-center">{{ $item->karyawan->jabatan->nama_jabatan }}</td>
                                        <td class="text-center">{{ $item->karyawan->Cabang->nama_cabang }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                        <td class="text-center">Rp {{ number_format($item->jumlah, 0) }}</td>
                                        <td class="text-center">{{ $item->keterangan }}</td>
                                        <td class="text-center">
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
                                        <td class="text-center">
                                            <a href="{{ route('admin.cashbon.show', $item->id) }}" class="btn btn-sm btn-info my-1" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if ($item->status == 'pending')
                                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPengajuan{{ $item->kode_cashbon }}" data-id="{{ $item->id }}" title="Aksi"><i class="bi bi-box-arrow-right"></i></a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalPembatalan{{ $item->kode_cashbon }}" data-id="{{ $item->id }}" title="Batalkan">
                                                    <i class="bi bi-x-square"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Modal Pengajuan -->
                                    <div class="modal fade" id="modalPengajuan{{ $item->kode_cashbon }}" tabindex="-1" aria-labelledby="modalPengajuan{{ $item->kode_cashbon }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalPengajuan{{ $item->kode_cashbon }}Label">Pengajuan Cashbon {{ $item->karyawan->nama_lengkap }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.cashbon.persetujuan') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id" value="{{ $item->id }}">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <select name="status" id="status" class="form-control">
                                                                        <option value="diterima">Diterima </option>
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
                                    <div class="modal fade" id="modalPembatalan{{ $item->kode_cashbon }}" tabindex="-1" aria-labelledby="modalPembatalan{{ $item->kode_cashbon }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalPembatalan{{ $item->kode_cashbon }}Label">Konfirmasi Pembatalan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin membatalkan pengajuan cashbon {{ $item->karyawan->nama_lengkap }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.cashbon.pembatalan') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" id="cancelId" value="{{ $item->id }}">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@endsection

@push('myscript')
<script>
    let table = new DataTable('#dataTable');

    $('#modalPengajuan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var cashbon_id = button.data('id'); // Ambil nilai dari atribut data-id

        // Masukkan nilai cashbon_Id ke dalam input hidden
        var modal = $(this);
        modal.find('.modal-body #id').val(cashbon_id);
    });

    // Script untuk mengisi ID cashbon ke dalam modal
    $('#modalPembatalan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var id = button.data('id'); // Ambil ID dari data-id
        var modal = $(this);
        modal.find('#cancelId').val(id); // Set ID ke input hidden
    });
</script>


    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup',
                    title: 'custom-title',
                    content: 'custom-content',
                    confirmButton: 'custom-confirm-button'
                }
            });
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup',
                    title: 'custom-title',
                    content: 'custom-content',
                    confirmButton: 'custom-confirm-button'
                }
            });
        });
    </script>
    @endif
@endpush
@endif
