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
    <h1 class="h3 mb-0 text-gray-800">THR Karyawan</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12">

                <a href="{{ route('admin.thr.create') }}" class="btn btn-primary mb-3">Tambah THR</a>
            </div>
        </div>

        {{-- form cari data THR --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.thr') }}" method="GET">
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
                        <div class="col-4">
                            <div class="form-group">
                                <div class="icon-placeholder">
                                    <i class="bi bi-upc-scan"></i>
                                    <input type="text" class="form-control" id="nik" name="nik" placeholder=" NIK" value="{{ Request('nik') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <div class="icon-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder=" Nama Lengkap" value="{{ Request('nama_lengkap') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_jabatan" id="kode_jabatan" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatan as $j)
                                        <option value="{{ $j->kode_jabatan }}" {{ Request('kode_jabatan') == $j->kode_jabatan ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control">
                                    <option value="">Pilih cabang</option>
                                    @foreach ($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control">
                                    <option value="">Pilih lokasi Penugasan</option>
                                    @foreach ($lokasi_penugasan as $lp)
                                        <option value="{{ $lp->kode_lokasi_penugasan }}" data-cabang="{{ $lp->kode_cabang }}">{{ $lp->nama_lokasi_penugasan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Pilih Status Pengajuan</option>
                                    <option value="Disetujui" {{ Request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ Request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="Pending" {{ Request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
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
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                {{-- <th class="text-center">NIK</th> --}}
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Lokasi Penugasan</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">THR</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                {{-- <th class="text-center">Catatan</th> --}}
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($thr as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                {{-- <td class="text-center">{{ $item->nik }}</td> --}}
                                <td class="text-center">{{ $item->karyawan->nama_lengkap }}</td>
                                <td class="text-center">{{$item->jabatan->nama_jabatan }}</td>
                                <td class="text-center">{{$item->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                                <td class="text-center">{{$item->kantorCabang->nama_cabang }}</td>
                                <td class="text-center">{{$item->nama_thr }}</td>
                                <td class="text-center">{{$item->tahun }}</td>
                                <td class="text-center">Rp {{ number_format($item->jumlah_thr, 2) }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_penyerahan)->translatedFormat('d F Y') }}</td>
                                <td class="text-center">
                                    @if ($item->status == 'Pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($item->status == 'Disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($item->status == 'Ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Diketahui</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">{{$item->notes }}</td> --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.thr.show', $item->kode_thr) }}" class="btn btn-info mt-1" title="Lihat"><i class="bi bi-list"></i></a>
                                    <a href="{{ route('admin.thr.edit', $item->kode_thr) }}" class="btn btn-warning mt-1" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('admin.thr.delete', $item->kode_thr) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger delete-confirm mt-1"
                                            title="Delete"
                                            data-nama="{{ $item->karyawan->nama_lengkap }}"
                                            data-nama-thr="{{ $item->nama_thr }}"
                                            data-tahun="{{ $item->tahun }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    {{-- <form action="#" method="GET" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $jabatan->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('kode_cabang');
        const lokasiSelect = document.getElementById('kode_lokasi_penugasan');

        cabangSelect.addEventListener('change', function() {
            const selectedCabang = this.value;

            // Tampilkan semua lokasi penugasan yang sesuai dengan cabang yang dipilih
            Array.from(lokasiSelect.options).forEach(option => {
                if (option.dataset.cabang === selectedCabang || selectedCabang === "") {
                    option.style.display = 'block'; // Tampilkan option
                } else {
                    option.style.display = 'none'; // Sembunyikan option
                }
            });

            // Reset pilihan lokasi penugasan jika tidak ada yang sesuai
            if (!Array.from(lokasiSelect.options).some(option => option.style.display === 'block')) {
                lokasiSelect.value = ""; // Reset
            }
        });
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
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
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
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif
<script>
    let table = new DataTable('#dataTable');

    $(document).ready(function() {
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            // Ambil data dari atribut data
            var namaKaryawan = $(this).data('nama');
            var namaThr = $(this).data('nama-thr');
            var tahun = $(this).data('tahun');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data THR untuk " + namaKaryawan + " (" + namaThr + " - " + tahun + ") akan dihapus permanen!",
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

    // Validasi form
        $("#formGaji").submit(function(){
            var kode_gaji = $("#kode_gaji").val();
            var kode_jabatan = $("#kode_jabatan").val();
            var nama_gaji = $("#nama_gaji").val();
            var jumlah_gaji = $("#jumlah_gaji").val();

            if(kode_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_gaji").focus();
                });
                return false;
            } else if (kode_jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jabatan").focus();
                });
                return false;
            } else if (nama_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_gaji").focus();
                });
                return false;
            } else if (jumlah_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jumlah_gaji").focus();
                });
                return false;
            }
        });

        // Mengisi Data pada Modal Edit Gaji
        $('#modalEditGaji').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var kode_gaji = button.data('kode_gaji');
            var kode_jabatan = button.data('kode_jabatan');
            var nama_gaji = button.data('nama_gaji');
            var jumlah_gaji = button.data('jumlah_gaji');

            var modal = $(this);
            modal.find('.modal-body #edit_kode_gaji').val(kode_gaji);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
            modal.find('.modal-body #edit_nama_gaji').val(nama_gaji);
            modal.find('.modal-body #edit_jumlah_gaji').val(jumlah_gaji);

            // Update URL form action dengan username yang sesuai
            var formAction = "{{ route('admin.gaji.update', ['kode_gaji' => ':kode_gaji']) }}";
            formAction = formAction.replace(':kode_gaji', kode_gaji);
            $('#formEditGaji').attr('action', formAction);
        });
</script>


@endpush


@endsection
