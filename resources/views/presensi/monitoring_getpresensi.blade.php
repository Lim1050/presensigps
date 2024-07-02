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
        {{-- <td>{{ $item->jabatan }}</td> --}}
        <td>{{ $item->nama_departemen }}</td>
        <td>{{ $item->jam_masuk }}</td>
        <td><img src="{{ Storage::url($item->foto_masuk) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="..."></td>
        <td>{!! $item->jam_keluar != null ? $item->jam_keluar : '<span class="badge bg-danger" style="color: white">Belum Absen Pulang</span>' !!}</td>
        <td>
            @if ($item->foto_keluar != null)
                <img src="{{ Storage::url($item->foto_keluar) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
            @else
                <span class="badge bg-danger" style="color: white">Belum Absen Pulang</span>
            @endif
        </td>
        <td>
            @if ($item->jam_masuk >= '09:00')
                @php
                    $terlambat = selisih('09:00:00', $item->jam_masuk);
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
