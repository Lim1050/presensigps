@php
// Function Untuk Menghitung Selisih Jam
    function selisih($jam_masuk, $jam_keluar)
    {
        // Memastikan format waktu valid
        if (!preg_match("/^\d{2}:\d{2}:\d{2}$/", $jam_masuk) || !preg_match("/^\d{2}:\d{2}:\d{2}$/", $jam_keluar)) {
            return "Format waktu tidak valid";
        }

        list($h1, $m1, $s1) = explode(":", $jam_masuk);
        list($h2, $m2, $s2) = explode(":", $jam_keluar);

        // Menghitung waktu dalam detik
        $dtAwal = mktime($h1, $m1, $s1);
        $dtAkhir = mktime($h2, $m2, $s2);

        // Menghitung selisih dalam detik
        $dtSelisih = $dtAkhir - $dtAwal;

        // Jika selisih negatif, berarti jam keluar lebih awal dari jam masuk
        if ($dtSelisih < 0) {
            return "Jam keluar lebih awal dari jam masuk";
        }

        // Menghitung total jam dan menit
        $totalmenit = $dtSelisih / 60;
        $jam = floor($totalmenit / 60);
        $sisamenit = $totalmenit % 60;

        return $jam . " jam " . round($sisamenit) . " menit";
    }
@endphp
@foreach ($presensi as $item)
<tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td>{{ $item->nik }}</td>
    <td>{{ $item->nama_lengkap ?? 'Tidak Diketahui' }}</td>
    <td>{{ $item->nama_jabatan ?? 'Tidak Ada Jabatan' }}</td>
    <td>{{ $item->nama_lokasi_penugasan ?? 'Tidak Ada Lokasi' }}</td>
    <td>
        @if ($item->status == "hadir")
            {{ $item->nama_jam_kerja }} <br> ({{ $item->jam_masuk_kerja ?? 'Tidak Ada Jam Masuk' }} s/d {{ $item->jam_pulang_kerja ?? 'Tidak Ada Jam Pulang' }})
        @else
            -
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            {{ $item->jam_masuk ?? 'Tidak Ada Jam Masuk' }}
        @else
            -
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            <a href="#" class="showImageMasuk" data-image="{{ Storage::url($item->foto_masuk) }}" data-toggle="modal" data-target="#modalShowImageMasuk">
                <img src="{{ Storage::url($item->foto_masuk) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="Foto Masuk">
            </a>
        @else
            -
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            {!! $item->jam_keluar != null ? $item->jam_keluar : '<span class="badge bg-danger" style="color: white">Belum Absen Pulang</span>' !!}
        @else
            -
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            @if ($item->foto_keluar != null)
                <a href="#" class="showImageKeluar" data-image="{{ Storage::url($item->foto_keluar) }}" data-toggle="modal" data-target="#modalShowImageKeluar">
                    <img src="{{ Storage::url($item->foto_keluar) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="Foto Keluar">
                </a>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="75" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                    <path d="M12.5 16a3. 5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                </svg>
            @endif
        @else
            -
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            <span class="badge bg-success" style="color: white">{{ $item->status }}</span>
        @else
            <span class="badge {{ $item->status == 'izin' ? 'bg-primary' : ($item->status == 'sakit' ? 'bg-danger' : 'bg-secondary') }}" style="color: white">{{ $item->status }}</span>
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            @if ($item->jam_masuk >= $item->jam_masuk_kerja)
                @php
                    $terlambat = selisih($item->jam_masuk_kerja, $item->jam_masuk);
                @endphp
                <span class="badge bg-warning" style="color: white">Terlambat {{ $terlambat }}</span>
            @else
                <span class="badge bg-success" style="color: white">Tepat Waktu</span>
            @endif
        @else
            {{ $item->keterangan_izin ?? 'Tidak Ada Keterangan' }}
        @endif
    </td>
    <td>
        @if ($item->status == "hadir")
            <a href="#" class="btn btn-primary showmap" id="{{ $item->id }}" data-toggle="modal" data-target="#modalShowMap"> <i class="bi bi-geo-alt"></i></a>
        @else
            -
        @endif
    </td>
</tr>
@endforeach

<script>
    $(function(){
        $(".showmap").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url:'/admin/presensi/tampilkanpeta',
                data:{
                    _token:"{{ csrf_token() }}",
                    id:id,
                },
                cache:false,
                success:function(respond){
                    $("#loadmap").html(respond);
                }
            });
        });
    });

    $(document).ready(function() {
        $('.showImageMasuk').on('click', function() {
            var imageUrl = $(this).data('image');
            $('#modalImageMasuk').attr('src', imageUrl);
        });
    });
    $(document).ready(function() {
        $('.showImageKeluar').on('click', function() {
            var imageUrl = $(this).data('image');
            $('#modalImageKeluar').attr('src', imageUrl);
        });
    });
</script>
