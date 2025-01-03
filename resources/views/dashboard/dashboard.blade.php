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
                                @if ($presensi_hari_ini != null && $presensi_hari_ini->foto_masuk && $presensi_hari_ini->status == 'hadir')
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
                                    <span>{{ $presensi_hari_ini != null && $presensi_hari_ini->jam_masuk ? $presensi_hari_ini->jam_masuk : 'Belum Absen' }}</span>
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
                <table class="table" id="lemburTable">
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
                            <tr data-id="{{ $lembur->kode_lembur }}">
                                <td>
                                    {{ \Carbon\Carbon::parse($lembur->tanggal_presensi)->translatedFormat('d-m-Y') }}
                                    @if($lembur->catatan_lembur)
                                        <br>
                                        <button class="btn btn-sm btn-secondary mt-1 btn-keterangan"
                                        data-keterangan="{{ $lembur->catatan_lembur }}"
                                        data-status="{{ $lembur->status }}"
                                        data-alasan="{{ $lembur->alasan_penolakan }}""
                                        >
                                            Keterangan
                                        </button>
                                    @endif
                                </td>
                                <td>{{ date("H:i", strtotime($lembur->waktu_mulai)) }}</td>
                                <td>{{ date("H:i", strtotime($lembur->waktu_selesai)) }}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="badge mb-1
                                            @switch($lembur->status)
                                                @case('pending') badge-warning @break
                                                @case('disetujui') badge-success @break
                                                @case('ditolak') badge-danger @break
                                                @default badge-secondary
                                            @endswitch
                                            status-badge">
                                            {{ ucfirst($lembur->status) }}
                                        </span>

                                        @if($lembur->status == 'pending')
                                            <button class="btn btn-sm btn-primary btn-approval mt-1">
                                                Tindakan
                                            </button>
                                        {{-- @elseif($lembur->status == 'ditolak' && $lembur->alasan_penolakan)
                                            <button class="btn btn-sm btn-danger btn-alasan mt-1"
                                                data-alasan="{{ $lembur->alasan_penolakan }}">
                                                Lihat Alasan
                                            </button> --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data lembur</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($totalLembur > 5)
                    <div class="card-footer text-center p-0">
                        <a href="#"
                        class="btn btn-block btn-outline-secondary rounded-0 py-2">
                            Lihat Selengkapnya
                            <span class="badge badge-secondary ml-2">
                                {{ $totalLembur - 5 }}
                            </span>
                        </a>
                    </div>
                @endif
            </div>
            {{-- Tambahkan button Selengkapnya --}}
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
                    .historycontent{ display: flex; }
                    .datapresensi{ margin-left: 10px; }
                </style>
                {{-- @foreach ($history_bulan_ini as $bulan_ini)
                    @if ($bulan_ini->status == "hadir" && $bulan_ini->kode_jam_kerja != null)
                        <div class="card mb-1">
                            <div class="card-body">
                                <div class="historycontent">
                                    <div class="iconpresensi">
                                        @php
                                            $jadwalMasuk = $bulan_ini->jam_kerja_masuk;
                                            $tepatWaktu = $bulan_ini->jam_masuk <= $jadwalMasuk;
                                        @endphp
                                        @if ($tepatWaktu)
                                            <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                                        @else
                                            <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="alert"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="datapresensi">
                                        <h3 style="line-height: 3px">
                                            {{ $bulan_ini->nama_jam_kerja }}
                                            <small>({{ date("H:i",strtotime($bulan_ini->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($bulan_ini->jam_pulang)) }})</small>
                                        </h3>
                                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                        <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                            {{ date("H:i",strtotime($bulan_ini->jam_masuk)) }}
                                        </span> -
                                        <span class="text-danger">
                                            {{ $bulan_ini->jam_keluar ? date("H:i",strtotime($bulan_ini->jam_keluar)) : 'Belum Absen' }}
                                        </span>
                                        <div id="keterangan" class="mt-0">
                                            @php
                                                $jadwal_jam_masuk = $bulan_ini->tanggal_presensi . " " . $jadwalMasuk;
                                                $jam_presensi = $bulan_ini->tanggal_presensi . " " . $bulan_ini->jam_masuk;
                                                $hitungjamterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
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
                                            <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                                {{ $tepatWaktu ? 'Tepat Waktu' : "Terlambat $deskripsiTerlambat" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($bulan_ini->status == "hadir" && $bulan_ini->kode_lembur != null)
                    <div class="card mb-1">
                            <div class="card-body">
                                <div class="historycontent">
                                    <div class="iconpresensi">
                                        @php
                                            $jadwalMasuk = $bulan_ini->jam_kerja_masuk;
                                            $tepatWaktu = $bulan_ini->jam_masuk <= $jadwalMasuk;
                                        @endphp
                                        @if ($tepatWaktu)
                                            <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                                        @else
                                            <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="alert"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="datapresensi">
                                        <h3 style="line-height: 3px">
                                            {{ $bulan_ini->nama_jam_kerja }}
                                            <small>({{ date("H:i",strtotime($bulan_ini->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($bulan_ini->jam_pulang)) }})</small>
                                        </h3>
                                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                        <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                            {{ date("H:i",strtotime($bulan_ini->jam_masuk)) }}
                                        </span> -
                                        <span class="text-danger">
                                            {{ $bulan_ini->jam_keluar ? date("H:i",strtotime($bulan_ini->jam_keluar)) : 'Belum Absen' }}
                                        </span>
                                        <div id="keterangan" class="mt-0">
                                            @php
                                                $jadwal_jam_masuk = $bulan_ini->tanggal_presensi . " " . $jadwalMasuk;
                                                $jam_presensi = $bulan_ini->tanggal_presensi . " " . $bulan_ini->jam_masuk;
                                                $hitungjamterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
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
                                            <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                                {{ $tepatWaktu ? 'Tepat Waktu' : "Terlambat $deskripsiTerlambat" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($bulan_ini->status=="izin" || $bulan_ini->status=="sakit" || $bulan_ini->status=="cuti")
                        <!-- Kode untuk status izin, sakit, dan cuti tetap sama -->
                    @endif
                @endforeach --}}

                @foreach ($history_bulan_ini as $presensi)
                    @if ($presensi['status'] == "hadir")
                        <div class="card mb-1">
                            <div class="card-body">
                                <p class="mb-2">
                                    {{ \Carbon\Carbon::parse($presensi['tanggal_presensi'])->format('d-m-Y') }}
                                </p>
                                {{-- Jam Kerja Normal --}}
                                @if ($presensi['kode_jam_kerja'] && $presensi['jam_kerja_masuk'])
                                    <div class="historycontent mb-2">
                                        <div class="iconpresensi">
                                            @php
                                                $jadwalMasuk = $presensi['jam_kerja_masuk'];
                                                $tepatWaktu = \Carbon\Carbon::parse($presensi['jam_masuk'])->lte(\Carbon\Carbon::parse($jadwalMasuk));
                                            @endphp
                                            @if ($tepatWaktu)
                                                <ion-icon style="font-size: 48px" name="checkmark-circle-outline" class="text-success"></ion-icon>
                                            @else
                                                <ion-icon style="font-size: 48px" name="alert-circle-outline" class="text-warning"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="datapresensi">
                                            <h3 style="line-height: 3px">
                                                {{ $presensi['nama_jam_kerja'] }}
                                                <small>({{ \Carbon\Carbon::parse($presensi['jam_kerja_masuk'])->format('H:i') }} - {{ \Carbon\Carbon::parse($presensi['jam_pulang'])->format('H:i') }})</small>
                                            </h3>
                                            {{-- <h4 style="margin: 0px !important;">
                                                {{ \Carbon\Carbon::parse($presensi['tanggal_presensi'])->format('d-m-Y') }}
                                            </h4> --}}
                                            <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                                {{ $presensi['jam_masuk'] ? \Carbon\Carbon::parse($presensi['jam_masuk'])->format('H:i') : 'Belum Absen' }}
                                            </span> -
                                            <span class="text-danger">
                                                {{ $presensi['jam_keluar'] ? \Carbon\Carbon::parse($presensi['jam_keluar'])->format('H:i') : 'Belum Absen' }}
                                            </span>

                                            <div id="keterangan" class="mt-0">
                                                @php
                                                    $jadwal_jam_masuk = \Carbon\Carbon::parse($presensi['tanggal_presensi'] . ' ' . $jadwalMasuk);
                                                    $jam_presensi = $presensi['jam_masuk']
                                                        ? \Carbon\Carbon::parse($presensi['tanggal_presensi'] . ' ' . $presensi['jam_masuk'])
                                                        : $jadwal_jam_masuk;

                                                    $diff = $jadwal_jam_masuk->diff($jam_presensi);
                                                    $deskripsiTerlambat = '';

                                                    if ($diff->h > 0) {
                                                        $deskripsiTerlambat .= $diff->h . ' jam';
                                                    }

                                                    if ($diff->i > 0) {
                                                        $deskripsiTerlambat .= ($deskripsiTerlambat ? ' ' : '') . $diff->i . ' menit';
                                                    }
                                                @endphp
                                                <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                                                    {{ $presensi['jam_masuk'] ?
                                                        ($tepatWaktu ? 'Tepat Waktu' : "Terlambat $deskripsiTerlambat") :
                                                        'Belum Absen'
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Lembur --}}
                                @if ($presensi['mulai_lembur'] && $presensi['selesai_lembur'])
                                    <div class="historycontent">
                                        <div class="iconpresensi">
                                            <ion-icon style="font-size: 48px" name="time-outline" class="text-primary"></ion-icon>
                                        </div>
                                        <div class="datapresensi">
                                            <h3 style="line-height: 3px">
                                                Lembur
                                            </h3>
                                            <span>{{ ucfirst(strtolower($presensi['jenis_absen_lembur'] ?? 'reguler')) }} ({{ \Carbon\Carbon::parse($presensi['lembur']['waktu_mulai'])->format('H:i') }} - {{ \Carbon\Carbon::parse($presensi['lembur']['waktu_selesai'])->format('H:i') }})</span>
                                            {{-- <h4 style="margin: 0px !important;">
                                                {{ \Carbon\Carbon::parse($presensi['tanggal_presensi'])->format('d-m-Y') }}
                                            </h4> --}} <br>
                                            <span class="text-primary">
                                                {{ \Carbon\Carbon::parse($presensi['mulai_lembur'])->format('H:i') }}
                                            </span> -
                                            <span class="text-danger">
                                                {{ \Carbon\Carbon::parse($presensi['selesai_lembur'])->format('H:i') }}
                                            </span>
                                            {{-- <div id="keterangan" class="mt-0">
                                                <span class="text-primary">
                                                    @php
                                                        $mulai = \Carbon\Carbon::parse($presensi['mulai_lembur']);
                                                        $selesai = \Carbon\Carbon::parse($presensi['selesai_lembur']);
                                                        $durasi = $selesai->diff($mulai);
                                                    @endphp
                                                    {{ $durasi->format('%h jam %i menit') }}
                                                </span>
                                            </div> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif (in_array($presensi['status'], ['izin', 'sakit', 'cuti']))
                        {{-- Tambahkan logika untuk status izin, sakit, dan cuti --}}
                        <div class="card mb-1">
                            <div class="card-body">
                                <p class="mb-2">
                                    {{ \Carbon\Carbon::parse($presensi['tanggal_presensi'])->format('d-m-Y') }}
                                </p>
                                <div class="historycontent">
                                    <div class="iconpresensi">
                                        @if ($presensi['status'] == "izin")
                                        <ion-icon style="font-size: 40px;" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                                        @elseif ($presensi['status'] == "sakit")
                                        <ion-icon style="font-size: 40px;" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="checkmark"></ion-icon>
                                        @elseif ($presensi['status'] == "cuti")
                                        <ion-icon style="font-size: 40px;" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="checkmark"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="datapresensi">
                                        <h3 style="line-height: 3px">
                                            {{ ucfirst($presensi['status']) }}
                                        </h3>
                                        {{-- <h4 style="margin: 0px !important;">
                                            {{ \Carbon\Carbon::parse($presensi['tanggal_presensi'])->format('d-m-Y') }}
                                        </h4> --}}
                                        <span>
                                            {{ $presensi['keterangan'] ?? 'Tidak ada keterangan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if($totalPresensi > 5)
                    <div class="card text-center p-0">
                        <a href="{{ route('presensi.history') }}"
                        class="btn btn-block btn-outline-white rounded-0 py-2">
                            Lihat Selengkapnya
                            <span class="badge badge-secondary ml-2">
                                {{ $totalPresensi - 5 }}
                            </span>
                        </a>
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                @foreach ($leaderboards as $leaderboard)
                    <li>
                        <div class="item">
                            @php
                                $path = Storage::url("uploads/karyawan/".$leaderboard->foto);
                                $jadwalMasuk = $leaderboard->mulai_lembur ?? $leaderboard->jam_kerja_masuk;
                                $tepatWaktu = $leaderboard->jam_masuk && $jadwalMasuk ? $leaderboard->jam_masuk <= $jadwalMasuk : false;
                            @endphp
                            <img src="{{ url($path) }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    {{ $leaderboard->nama_lengkap }}
                                    <br>
                                    <small class="text-muted">{{ $leaderboard->kode_jabatan }}</small>
                                </div>
                                @if ($leaderboard->status == 'hadir')
                                    @if($leaderboard->jam_masuk)
                                        <span class="badge {{ $tepatWaktu ? 'badge-success' : 'badge-warning' }}">
                                            {{ $tepatWaktu ? date('H:i', strtotime($leaderboard->jam_masuk)) : 'Telat ' . date('H:i', strtotime($leaderboard->jam_masuk)) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Belum Absen</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">{{ $leaderboard->status }}</span>
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
document.addEventListener('DOMContentLoaded', function() {
    const lemburTable = document.getElementById('lemburTable');

    // Delegasi event untuk tombol keterangan
    lemburTable.addEventListener('click', function(e) {
        const btnKeterangan = e.target.closest('.btn-keterangan');
        if (btnKeterangan) {
            let message = btnKeterangan.dataset.keterangan; // Ambil keterangan lembur
            const status = btnKeterangan.dataset.status; // Ambil status lembur
            const alasan = btnKeterangan.dataset.alasan; // Ambil alasan penolakan

            // Jika status adalah 'ditolak', tambahkan alasan ke dalam pesan
            if (status === 'ditolak' && alasan) {
                message += `<br><br><strong style="color: red;">Alasan Penolakan:</strong><br><span>${alasan}</span>`;
            }

            Swal.fire({
                title: 'Keterangan Lembur',
                html: message,
                icon: 'info',
                confirmButtonText: 'Tutup'
            });
        }

        // Delegasi event untuk tombol alasan
        // const btnAlasan = e.target.closest('.btn-alasan');
        // if (btnAlasan) {
        //     Swal.fire({
        //         title: 'Alasan Penolakan',
        //         text: btnAlasan.dataset.alasan,
        //         icon: 'warning',
        //         confirmButtonText: 'Tutup'
        //     });
        // }

        // Delegasi event untuk tombol approval
        const btnApproval = e.target.closest('.btn-approval');
        if (btnApproval) {
            const row = btnApproval.closest('tr');
            const lemburId = row.dataset.id;

            Swal.fire({
                title: 'Persetujuan Lembur',
                text: "Pilih tindakan untuk lembur ini",
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Setujui',
                denyButtonText: 'Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateLemburStatus(lemburId, 'disetujui', row);
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Alasan Penolakan',
                        input: 'textarea',
                        inputPlaceholder: 'Masukkan alasan penolakan',
                        showCancelButton: true,
                        confirmButtonText: 'Tolak',
                        cancelButtonText: 'Batal',
                        inputValidator: (value) => {
                            if (!value) return 'Alasan penolakan wajib diisi!';
                        }
                    }).then((alasanResult) => {
                        if (alasanResult.isConfirmed) {
                            updateLemburStatus(lemburId, 'ditolak', row, alasanResult.value);
                        }
                    });
                }
            });
        }
    });

    function updateLemburStatus(id, status, row, alasanPenolakan = null) {
        fetch(`/lembur/${id}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                alasan_penolakan: alasanPenolakan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusCell = row.querySelector('td:last-child');
                const statusBadge = statusCell.querySelector('.status-badge');

                // Update status badge
                statusBadge.className = `badge mb-1
                    ${status === 'disetujui' ? 'badge-success' : 'badge-danger'}
                    status-badge`;
                statusBadge.textContent = status === 'disetujui' ? 'Disetujui' : 'Ditolak';

                // Remove action button
                const actionButtons = statusCell.querySelectorAll('.btn-approval, .btn-alasan');
                actionButtons.forEach(btn => btn.remove());

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: status === 'disetujui'
                        ? 'Lembur telah disetujui'
                        : 'Lembur telah ditolak',
                    didClose: () => {
                        // Refresh halaman setelah sweet alert ditutup
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan. Silakan coba lagi.'
            });
        });
    }
});
</script>
@endpush
@endsection
