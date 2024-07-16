@php
    // Function Untuk Menghitung Selisih Jam
    function selisih($jam_masuk, $jam_keluar)
    {
        list($h, $m, $s) = explode(":", $jam_masuk);
        $dtAwal = mktime($h, $m, $s, "1", "1", "1");
        list($h, $m, $s) = explode(":", $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode(".", $totalmenit / 60);
        $sisamenit = ($totalmenit / 60) - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];
        return $jml_jam . " jam " . round($sisamenit2) . " menit";
    }
@endphp
@foreach ($presensi as $item)

    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $item->nik }}</td>
        <td>{{ $item->nama_lengkap }}</td>
        <td>{{ $item->jabatan }}</td>
        <td>{{ $item->nama_departemen }}</td>
        <td>{{ $item->nama_jam_kerja }} <br> ({{ $item->jam_masuk_kerja }} s/d {{ $item->jam_pulang_kerja }})</td>
        <td>{{ $item->jam_masuk }}</td>
        <td><img src="{{ Storage::url($item->foto_masuk) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="..."></td>
        <td>{!! $item->jam_keluar != null ? $item->jam_keluar : '<span class="badge bg-danger" style="color: white">Belum Absen Pulang</span>' !!}</td>
        <td>
            @if ($item->foto_keluar != null)
                <img src="{{ Storage::url($item->foto_keluar) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="75" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                </svg> <br>
                {{-- <span class="badge bg-danger" style="color: white">Belum Absen Pulang</span> --}}
            @endif
        </td>
        <td>
            @if ($item->jam_masuk >= $item->jam_masuk_kerja)
                @php
                    $terlambat = selisih($item->jam_masuk_kerja, $item->jam_masuk);
                @endphp
                <span class="badge bg-warning" style="color: white">Terlambat {{ $terlambat }}</span>
            @else
                <span class="badge bg-success" style="color: white">Tepat Waktu</span>
            @endif
        </td>
        <td>
            <a href="#" class="btn btn-primary showmap" id="{{ $item->id }}" data-toggle="modal" data-target="#modalShowMap"> <i class="bi bi-geo-alt"></i></a>
        </td>
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
</script>
