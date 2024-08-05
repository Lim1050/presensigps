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
                    @if ($item->jam_masuk < $item->jam_kerja_masuk)
                        <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                    @else
                        <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="checkmark"></ion-icon>
                    @endif
                    {{-- <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon> --}}
                </div>
                <div class="datapresensi">
                    <h3 style="line-height: 3px">{{ $item->nama_jam_kerja }} <small>({{ date("H:i",strtotime($item->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($item->jam_pulang)) }})</small></h3>

                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                    <span class="{{ $item->jam_masuk < $item->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $item->jam_masuk < $item->jam_kerja_masuk ? date("H:i",strtotime($item->jam_masuk)) : date("H:i",strtotime($item->jam_masuk))}}</span> -
                    <span class="text-danger">{{ $item->jam_keluar != null ? date("H:i",strtotime($item->jam_keluar)) : 'Belum Absen'}}</span>
                    <div id="keterangan" class="mt-0">
                        @php
                            // waktu ketika absen
                            $jam_masuk = date("H:i",strtotime($item->jam_masuk));
                            // waktu jadwal masuk
                            $jam_kerja_masuk = date("H:i",strtotime($item->jam_kerja_masuk));

                            $jadwal_jam_masuk = $item->tanggal_presensi." ".$jam_kerja_masuk;
                            $jam_presensi = $item->tanggal_presensi." ".$jam_masuk;
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
                        <span class="{{ $item->jam_masuk < $item->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $item->jam_masuk > $item->jam_kerja_masuk ? "Terlambat $deskripsiTerlambat" : "Tepat Waktu"}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif ($item->status=="izin")
    <div class="card mb-1">
        <div class="card-body">
            <div class="historycontent">
                <div class="iconpresensi">
                    <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                </div>
                <div class="datapresensi">
                    <h3 style="line-height: 3px">{{ strtoupper($item->status) }}
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
                    <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="checkmark"></ion-icon>
                </div>
                <div class="datapresensi">
                    <h3 style="line-height: 3px">{{ strtoupper($item->status) }}
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
                    <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="checkmark"></ion-icon>
                </div>
                <div class="datapresensi">
                    <h3 style="line-height: 3px">{{ strtoupper($item->status )}}
                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</h4>
                    <p>{{ $item->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
@endforeach
