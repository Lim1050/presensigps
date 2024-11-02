@if ($history->isEmpty())
    <div class="alert alert-warning">
        <p>Belum ada data</p>
    </div>
@endif
<style>
    .historycontent{
        display: flex;
    }
    .datapresensi{
        margin-left: 10px;
    }
</style>
@foreach ($history as $item)
    @if ($item->status == "hadir")
        <div class="card mb-1">
            <div class="card-body">
                <div class="historycontent">
                    <div class="iconpresensi">
                        @php
                            $jadwalMasuk = $item->jam_kerja_masuk;
                            $tepatWaktu = $item->jam_masuk <= $jadwalMasuk;
                        @endphp
                        @if ($tepatWaktu)
                            <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                        @else
                            <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="alert"></ion-icon>
                        @endif
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">
                            {{ $item->nama_jam_kerja }}
                            <small>({{ date("H:i",strtotime($item->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($item->jam_pulang)) }})</small>
                        </h3>
                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                        <span class="{{ $tepatWaktu ? 'text-success' : 'text-warning' }}">
                            {{ date("H:i",strtotime($item->jam_masuk)) }}
                        </span> -
                        <span class="text-danger">
                            {{ $item->jam_keluar != null ? date("H:i",strtotime($item->jam_keluar)) : 'Belum Absen' }}
                        </span>
                        <div id="keterangan" class="mt-0">
                            @php
                                $jadwal_jam_masuk = $item->tanggal_presensi." ".$item->jam_kerja_masuk;
                                $jam_presensi = $item->tanggal_presensi." ".$item->jam_masuk;
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
                        @if($item->mulai_lembur)
                            <div class="mt-1">
                                <span class="badge badge-info">
                                    Lembur: {{ date("H:i", strtotime($item->mulai_lembur)) }} - {{ date("H:i", strtotime($item->selesai_lembur)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif ($item->status=="izin")
        <div class="card mb-1">
            <div class="card-body">
                <div class="historycontent">
                    <div class="iconpresensi">
                        <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="izin"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">{{ strtoupper($item->status) }}</h3>
                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                        <p>{{ $item->keterangan }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($item->status=="sakit")
        <div class="card mb-1">
            <div class="card-body">
                <div class="historycontent">
                    <div class="iconpresensi">
                        <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="sakit"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">{{ strtoupper($item->status) }}</h3>
                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                        <p>{{ $item->keterangan }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($item->status=="cuti")
        <div class="card mb-1">
            <div class="card-body">
                <div class="historycontent">
                    <div class="iconpresensi">
                        <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="cuti"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">{{ strtoupper($item->status) }}</h3>
                        <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                        <p>{{ $item->keterangan }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
