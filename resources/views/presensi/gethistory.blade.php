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
@foreach ($history as $presensi)
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
                            <span>{{ ucfirst(strtolower($presensi['jenis_absen_lembur'] ?? '-')) }} ({{ \Carbon\Carbon::parse($presensi['lembur']['waktu_mulai'])->format('H:i') }} - {{ \Carbon\Carbon::parse($presensi['lembur']['waktu_selesai'])->format('H:i') }})</span>
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
