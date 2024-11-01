@extends('layouts.master')
@section('content')
<style>
    .logout{
        position:absolute;
        color: white;
        font-size: 15px;
        right: 5px;
    }
</style>
<div class="section gradasired" id="user-section">
    <a href="#" class="btn btn-primary logout" id="logoutBtn">
        <ion-icon name="log-out-outline"></ion-icon>Keluar
    </a>
    <div id="user-detail">
        <div class="avatar">
            @if ((Auth::guard('karyawan')->user()->foto))
                @php
                    $path = Storage::url("uploads/karyawan/".Auth::guard('karyawan')->user()->foto)
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded" style="max-height: 60px; max-width: 60px; width: auto; height: auto; object-fit: cover;">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">
                @php
                    $fullName = Auth::guard('karyawan')->user()->nama_lengkap;
                    $namaSingkat = '';
                    $parts = explode(' ', $fullName);

                    // Ambil kata pertama dan tambahkan langsung ke namaSingkat
                    $namaSingkat .= $parts[0] . ' ';

                    // Loop dimulai dari index 1 agar kata pertama tidak disingkat
                    for ($i = 1; $i < count($parts); $i++) {
                        // Ambil inisial dari setiap kata kecuali kata pertama
                        $namaSingkat .= strtoupper(substr($parts[$i], 0, 1));
                    }

                    // Tampilkan hasilnya
                    echo $namaSingkat;
                @endphp
            </h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->kode_jabatan }}</span>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->kode_cabang }}</span>
        </div>
    </div>
</div>

<div class="section mt-1" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('profile') }}" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('presensi.history') }}" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Riwayat</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('izin') }}" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Izin</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('keuangan') }}" class="primary" style="font-size: 40px;">
                            <ion-icon name="cash"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Keuangan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .center-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        }
</style>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            @if ($presensi_hari_ini != null && $presensi_hari_ini->status != 'hadir')
            <div class="col-12 text-center mb-1">
                @if ($presensi_hari_ini->status == 'izin')
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($presensi_hari_ini->status == 'sakit')
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($presensi_hari_ini->status == 'cuti')
                    <div class="card bg-secondary">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            @else
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensi_hari_ini != null && $presensi_hari_ini->status == 'hadir')
                                    @php
                                        $path = Storage::url($presensi_hari_ini->foto_masuk);
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'izin')
                                    <ion-icon name="reader-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'sakit')
                                    <ion-icon name="medkit-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'cuti')
                                    <ion-icon name="calendar-outline"></ion-icon>
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>

                            @if ($presensi_hari_ini == null || $presensi_hari_ini->status == 'hadir')
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensi_hari_ini != null ? $presensi_hari_ini->jam_masuk : 'Belum Absen' }}</span>
                                </div>
                            @else
                                <div class="presencedetail">
                                    <h4 class="presencetitle">{{ strtoupper($presensi_hari_ini->status) }}</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensi_hari_ini != null && $presensi_hari_ini->foto_keluar != null)
                                    @php
                                        $path = Storage::url($presensi_hari_ini->foto_keluar);
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'izin')
                                    <ion-icon name="reader-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'sakit')
                                    <ion-icon name="medkit-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'cuti')
                                    <ion-icon name="calendar-outline"></ion-icon>
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>

                            @if ($presensi_hari_ini != null && $presensi_hari_ini->foto_keluar != null)
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensi_hari_ini->jam_keluar != null ? $presensi_hari_ini->jam_keluar : 'Belum Absen' }}</span>
                                </div>
                            @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status != 'hadir')
                                <div class="presencedetail">
                                    <h4 class="presencetitle">{{ strtoupper($presensi_hari_ini->status)}}</h4>
                                </div>
                            @else
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>Belum Absen</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            <h3>Daftar Lembur Bulan {{ $monthName }} {{ $tahun_ini }}</h3>
            <div class="card">
                {{-- <div class="card-body"> --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftar_lembur as $lembur)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($lembur->tanggal_presensi)->translatedFormat('d-m-Y') }}
                                        <br>
                                        <a href="#" class="badge badge-primary keterangan-link" data-id="{{ $lembur->id }}">Keterangan</a>
                                        <span id="keterangan-{{ $lembur->id }}" style="display: none;">{{ $lembur->catatan_lembur }}</span>
                                    </td>
                                    <td>{{ date("H:i",strtotime($lembur->waktu_mulai)) }}</td>
                                    <td>{{ date("H:i",strtotime($lembur->waktu_selesai)) }}</td>
                                    <td>
                                        @if ($lembur->status == 'pending')
                                            <a href="#" class="badge badge-warning approval-link" data-id="{{ $lembur->id }}">Pending</a>
                                        @elseif ($lembur->status == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif ($lembur->status == 'ditolak')
                                            <span class="badge badge-danger" data-toggle="tooltip" title="Alasan: {{ $lembur->alasan_penolakan }}">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data lembur</td>
                                </tr>
                            @endforelse
                        </tbody>
                </table>
                {{-- </div> --}}
            </div>
        </div>
    </div>

<!-- Modal -->
{{-- <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Persetujuan Lembur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda ingin menyetujui lembur ini?</p>

                <!-- Form Alasan Penolakan (awalnya tersembunyi) -->
                <div id="rejectReasonForm" style="display: none;">
                    <div class="form-group">
                        <label for="alasan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_penolakan" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Alasan penolakan wajib diisi
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success approve-btn">Setujui</button>
                <button type="button" class="btn btn-danger reject-btn">Tolak</button>
                <!-- Tombol Konfirmasi Penolakan (awalnya tersembunyi) -->
                <button type="button" class="btn btn-danger confirm-reject-btn" style="display: none;">Konfirmasi Penolakan</button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal -->
{{-- <div class="modal fade" id="keteranganModal" tabindex="-1" aria-labelledby="keteranganModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keteranganModalLabel">Keterangan Lembur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="keteranganContent">
                <!-- Keterangan lembur akan ditampilkan di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> --}}

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $monthName }} {{ $tahun_ini }}</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_hadir }}</span>
                        <ion-icon name="checkmark-circle-outline" style="font-size: 1.6rem" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-success">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_sakit }}</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.6rem" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-danger">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_izin }}</span>
                        <ion-icon name="reader-outline" style="font-size: 1.6rem" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-primary">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_cuti }}</span>
                        <ion-icon name="calendar-outline" style="font-size: 1.6rem" class="text-secondary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-secondary">Cuti</span>
                    </div>
                </div>
            </div>
            {{-- <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_terlambat }}</span>
                        <ion-icon name="alert-circle-outline" style="font-size: 1.6rem" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-warning">Telat</span>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Daftar Kehadiran
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <style>
                    .historycontent{
                        display: flex;
                    }
                    .datapresensi{
                        margin-left: 10px;
                    }
                </style>
                @foreach ($history_bulan_ini as $bulan_ini)
                @if ($bulan_ini->status == "hadir")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    @if ($bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk)
                                        <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                                    @else
                                        <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="checkmark"></ion-icon>
                                    @endif
                                    {{-- <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon> --}}
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ $bulan_ini->nama_jam_kerja }} <small>({{ date("H:i",strtotime($bulan_ini->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($bulan_ini->jam_pulang)) }})</small></h3>

                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <span class="{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? date("H:i",strtotime($bulan_ini->jam_masuk)) : date("H:i",strtotime($bulan_ini->jam_masuk))}}</span> -
                                    <span class="text-danger">{{ $bulan_ini->jam_keluar != null ? date("H:i",strtotime($bulan_ini->jam_keluar)) : 'Belum Absen'}}</span>
                                    <div id="keterangan" class="mt-0">
                                        @php
                                            // waktu ketika absen
                                            $jam_masuk = date("H:i",strtotime($bulan_ini->jam_masuk));
                                            // waktu jadwal masuk
                                            $jam_kerja_masuk = date("H:i",strtotime($bulan_ini->jam_kerja_masuk));

                                            $jadwal_jam_masuk = $bulan_ini->tanggal_presensi." ".$jam_kerja_masuk;
                                            $jam_presensi = $bulan_ini->tanggal_presensi." ".$jam_masuk;
                                            $hitungjamterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
                                            // Konversi hasil $hitungjamterlambat menjadi format deskriptif
                                            list($hours, $minutes) = explode(':', $hitungjamterlambat);
                                            $deskripsiTerlambat = '';
                                            if ($hours > 0) {
                                                $deskripsiTerlambat .= (int)$hours . ' jam';
                                            }
                                            if ($minutes > 0) {
                                                if ($hours > 0) {
                                                    $deskripsiTerlambat .= ' ';
                                                }
                                                $deskripsiTerlambat .= (int)$minutes . ' menit';
                                            }
                                        @endphp
                                        <span class="{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $bulan_ini->jam_masuk > $bulan_ini->jam_kerja_masuk ? "Terlambat $deskripsiTerlambat" : "Tepat Waktu"}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="izin")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status) }}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="sakit")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status) }}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="cuti")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status )}}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboards as $leaderboard)
                    <li>
                        <div class="item">
                            @php
                                $path = Storage::url("uploads/karyawan/".$leaderboard->foto)
                            @endphp
                            <img src="{{ url($path) }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    {{ $leaderboard->nama_lengkap }}
                                    <br>
                                    <small class="text-muted">{{ $leaderboard->kode_jabatan }}</small>
                                </div>
                                @if ($leaderboard->status == 'hadir')
                                <span class="badge {{ $leaderboard->jam_masuk < $leaderboard->jam_kerja_masuk ? "badge-success" : "badge-warning"}}">{{ $leaderboard->jam_masuk < $leaderboard->jam_kerja_masuk ? $leaderboard->jam_masuk : "Telat " . $leaderboard->jam_masuk}}</span>
                                @else
                                <span class="badge badge-secondary">{{ $leaderboard->status}}</span>
                                @endif

                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutBtn = document.getElementById('logoutBtn');

        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke route logout
                    window.location.href = "{{ route('logout') }}";
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.keterangan-link').forEach(function (element) {
            element.addEventListener('click', function (event) {
                event.preventDefault();

                // Ambil data keterangan dari elemen tersembunyi berdasarkan ID lembur
                let lemburId = this.getAttribute('data-id');
                let keterangan = document.querySelector(`#keterangan-${lemburId}`).textContent;

                // Tampilkan SweetAlert dengan keterangan lembur
                Swal.fire({
                    title: 'Keterangan Lembur',
                    text: keterangan,
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $('.approval-link').on('click', function(e) {
        e.preventDefault();
        const lemburId = $(this).data('id');

        Swal.fire({
            title: 'Persetujuan Lembur',
            text: "Pilih tindakan untuk lembur ini",
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Setujui',
            denyButtonText: 'Tolak',
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika disetujui
                updateLemburStatus(lemburId, 'disetujui');
            } else if (result.isDenied) {
                // Jika ditolak, tampilkan input alasan
                Swal.fire({
                    title: 'Alasan Penolakan',
                    input: 'textarea',
                    inputLabel: 'Masukkan alasan penolakan',
                    inputPlaceholder: 'Ketik alasan penolakan di sini...',
                    inputAttributes: {
                        'aria-label': 'Ketik alasan penolakan di sini'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Konfirmasi Penolakan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Alasan penolakan wajib diisi!'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateLemburStatus(lemburId, 'ditolak', result.value);
                    }
                });
            }
        });
    });

    function updateLemburStatus(id, status, alasanPenolakan = null) {
        var data = {
            _token: '{{ csrf_token() }}',
            status: status
        };

        if (alasanPenolakan) {
            data.alasan_penolakan = alasanPenolakan;
        }

        $.ajax({
            url: '/lembur/' + id + '/update-status',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    var badgeClass = status === 'disetujui' ? 'badge-success' : 'badge-danger';
                    var badgeText = status === 'disetujui' ? 'Disetujui' : 'Ditolak';

                    $('a[data-id="' + id + '"]')
                        .removeClass('approval-link badge-warning')
                        .addClass(badgeClass)
                        .text(badgeText)
                        .removeAttr('data-toggle data-target');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: status === 'disetujui' ?
                            'Lembur telah disetujui' :
                            'Lembur telah ditolak'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.'
                });
            }
        });
    }
});
</script>
@endpush
@endsection
