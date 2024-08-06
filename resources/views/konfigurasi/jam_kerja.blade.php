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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jam Kerja</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-4">
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputJamKerja"><i class="bi bi-plus-lg"></i> Tambah Data Jam Kerja</a>
            </div>
        </div>

        {{-- form cari data jam kerja --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.jam.kerja') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_jam_kerja_cari" id="nama_jam_kerja_cari" class="form-control" placeholder="Cari Nama Jam Kerja" value="{{ Request('nama_jam_kerja') }}">
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode Jam Kerja</th>
                                <th>Nama Jam Kerja</th>
                                <th>Awal Jam Masuk</th>
                                <th>Jam Masuk</th>
                                <th>Akhir Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Lintas Hari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jam_kerja as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_jam_kerja }}</td>
                                <td class="text-center">{{ $item->nama_jam_kerja }}</td>
                                <td class="text-center">{{ $item->awal_jam_masuk }}</td>
                                <td class="text-center">{{ $item->jam_masuk }}</td>
                                <td class="text-center">{{ $item->akhir_jam_masuk }}</td>
                                <td class="text-center">{{ $item->jam_pulang }}</td>
                                <td class="text-center">
                                    @if ($item->lintas_hari == 1)
                                        <span class="badge bg-success" style="color: white"><i class="bi bi-check2"></i></span>
                                    @else
                                        <span class="badge bg-danger" style="color: white"><i class="bi bi-x-lg"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditJamKerja"
                                            data-kode_jam_kerja="{{ $item->kode_jam_kerja }}"
                                            data-nama="{{ $item->nama_jam_kerja }}"
                                            data-awal="{{ $item->awal_jam_masuk }}"
                                            data-masuk="{{ $item->jam_masuk }}"
                                            data-akhir="{{ $item->akhir_jam_masuk }}"
                                            data-pulang="{{ $item->jam_pulang }}"
                                            data-lintas_hari="{{ $item->lintas_hari }}"
                                            ><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.konfigurasi.jam.kerja.delete', $item->kode_jam_kerja) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $departemen->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Jam Kerja -->
<div class="modal fade" id="modalInputJamKerja" tabindex="-1" aria-labelledby="modalInputJamKerjaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputJamKerjaLabel">Input Data Jam Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.konfigurasi.jam.kerja.store') }}" method="POST" id="formJamKerja" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jam_kerja" name="kode_jam_kerja" placeholder=" Kode Jam Kerja">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-clock"></i>
                            <input type="text" class="form-control" id="nama_jam_kerja" name="nama_jam_kerja" placeholder=" Nama Jam Kerja">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" id="awal_jam_masuk" name="awal_jam_masuk" placeholder=" Awal Jam Masuk">
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" placeholder=" Jam Masuk">
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" id="akhir_jam_masuk" name="akhir_jam_masuk" placeholder=" Akhir Jam Masuk">
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" placeholder=" Jam Pulang">
                    </div>
                    <div class="form-group">
                        <select name="lintas_hari" id="lintas_hari" class="form-control">
                            <option value="">Lintas Hari</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="row mt-2">
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

<!-- Modal -->
<div class="modal fade" id="modalEditJamKerja" tabindex="-1" role="dialog" aria-labelledby="modalEditJamKerjaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJamKerjaLabel">Edit Jam Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="{{ route('admin.konfigurasi.jam.kerja.update', ['kode_jam_kerja' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_jam_kerja">Kode Jam Kerja</label>
                        <input type="text" class="form-control" id="kode_jam_kerja" name="kode_jam_kerja" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_jam_kerja">Nama Jam Kerja</label>
                        <input type="text" class="form-control" id="nama_jam_kerja" name="nama_jam_kerja">
                    </div>
                    <div class="form-group">
                        <label for="awal_jam_masuk">Awal Jam Masuk</label>
                        <input type="time" class="form-control" id="awal_jam_masuk" name="awal_jam_masuk">
                    </div>
                    <div class="form-group">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk">
                    </div>
                    <div class="form-group">
                        <label for="akhir_jam_masuk">Akhir Jam Masuk</label>
                        <input type="time" class="form-control" id="akhir_jam_masuk" name="akhir_jam_masuk">
                    </div>
                    <div class="form-group">
                        <label for="jam_pulang">Jam Pulang</label>
                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang">
                    </div>
                    <div class="form-group">
                        <select name="lintas_hari" id="lintas_hari" class="form-control">
                            <option value="">Lintas Hari</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('myscript')
<script>

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
            Swal.fire({
            title: "Terhapus!",
            text: "Data Sudah dihapus.",
            icon: "success"
            });
        }
        });
    });

    // Validasi form
        $("#formJamKerja").submit(function(){
            var kode_jam_kerja = $("#kode_jam_kerja").val();
            var nama_jam_kerja = $("#nama_jam_kerja").val();
            var awal_jam_masuk = $("#awal_jam_masuk").val();
            var jam_masuk = $("#jam_masuk").val();
            var akhir_jam_masuk = $("#akhir_jam_masuk").val();
            var jam_pulang = $("#jam_pulang").val();
            var lintas_hari = $("#lintas_hari").val();


            if(kode_jam_kerja==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Jam Kerja Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jam_kerja").focus();
                });
                return false;
            } else if (nama_jam_kerja==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jam Kerja Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_jam_kerja").focus();
                });
                return false;
            } else if (awal_jam_masuk==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Awal Jam Masuk Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#awal_jam_masuk").focus();
                });
                return false;
            } else if (jam_masuk==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jam Masuk Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jam_masuk").focus();
                });
                return false;
            } else if (akhir_jam_masuk==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Akhir Jam Masuk Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#akhir_jam_masuk").focus();
                });
                return false;
            } else if (jam_pulang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jam Pulang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jam_pulang").focus();
                });
                return false;
            } else if (lintas_hari==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Lintas Hari Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#lintas_hari").focus();
                });
                return false;
            }
        });

    // Send Data to modal edit jam kerja
        $('#modalEditJamKerja').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var kode_jam_kerja = button.data('kode_jam_kerja');
        var nama = button.data('nama');
        var awal = button.data('awal');
        var masuk = button.data('masuk');
        var akhir = button.data('akhir');
        var pulang = button.data('pulang');
        var lintas_hari = button.data('lintas_hari');

        var modal = $(this);
        modal.find('.modal-body #kode_jam_kerja').val(kode_jam_kerja);
        modal.find('.modal-body #nama_jam_kerja').val(nama);
        modal.find('.modal-body #awal_jam_masuk').val(awal);
        modal.find('.modal-body #jam_masuk').val(masuk);
        modal.find('.modal-body #akhir_jam_masuk').val(akhir);
        modal.find('.modal-body #jam_pulang').val(pulang);
        modal.find('.modal-body #lintas_hari').val(lintas_hari);

        // Update form action URL with the id
        var formAction = "{{ route('admin.konfigurasi.jam.kerja.update', ['kode_jam_kerja' => ':kode_jam_kerja']) }}";
        formAction = formAction.replace(':kode_jam_kerja', kode_jam_kerja);
        $('#editForm').attr('action', formAction);
    });
</script>


@endpush


@endsection
