@foreach ($presensi as $item)

    <tr>
        <td>{{ $loop->iteration }}</td>
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
        <td>{!! $item->jam_masuk >= '09:00' ? '<span class="badge bg-warning" style="color: white">Terlambat</span>' : '<span class="badge bg-success" style="color: white">Tepat Waktu</span>' !!}</td>
    </tr>
@endforeach
