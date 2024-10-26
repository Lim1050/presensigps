@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan.cashbon') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Cashbon {{ $cashbon->kode_cashbon }}</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div style="margin-top: 70px; margin-bottom: 70px">
    {{-- <h1>Daftar Pengajuan Cashbon</h1> --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


@if ($cashbon)
    <div class="row">
        <div class="col">
            <div class="card">
                {{-- <div class="card-header">

                </div> --}}
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <tbody>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $cashbon->karyawan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $cashbon->karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $cashbon->karyawan->jabatan->nama_jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Penugasan</th>
                                <td>{{ $cashbon->karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                            </tr>
                            <tr>
                                <th>Kantor Cabang</th>
                                <td>{{ $cashbon->karyawan->Cabang->nama_cabang }}</td>
                            </tr>
                            <tr>
                                <th>Kode Cashbon</th>
                                <td>{{ $cashbon->kode_cashbon }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($cashbon->status == 'diterima')
                                        <span class="badge badge-success">Diterima</span>
                                    @elseif ($cashbon->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>Rp {{ number_format($cashbon->jumlah, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $cashbon->keterangan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <td>{{ $cashbon->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Tambahkan informasi lain yang relevan sesuai kebutuhan -->
                </div>
                <div class="card-footer">
                    <a href="{{ route('keuangan.cashbon') }}" class="btn btn-secondary">Kembali</a>
                    @if ($cashbon->status == 'pending')
                    <a href="{{ route('keuangan.cashbon.edit', $cashbon->id) }}" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ route('keuangan.cashbon.delete', $cashbon->id) }}')">Delete</button>
                    @else
                    <button type="button"
                            class="btn btn-warning"
                            disabled
                            onclick="alert('Editing hanya dapat dilakukan saat status masih pending')"
                            style="opacity: 0.6; cursor: not-allowed;">
                        Edit
                    </button>
                    <button type="button"
                            class="btn btn-danger"
                            disabled
                            onclick="alert('Hapus hanya dapat dilakukan saat status masih pending')"
                            style="opacity: 0.6; cursor: not-allowed;">
                        Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <p>Cashbon tidak ditemukan.</p>
@endif
</div>
@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, buat permintaan DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Tambahkan metode DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Tambahkan form ke body dan submit
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
