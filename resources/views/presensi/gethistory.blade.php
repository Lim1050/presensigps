@if ($history->isEmpty())
    <div class="alert alert-warning">
        <p>Belum ada data</p>
    </div>
@endif
@foreach ($history as $item)
<ul class="listview image-listview">
    <li>
        <div class="item">
            @php
                $path_masuk = Storage::url($item->foto_masuk);
                $path_pulang = Storage::url($item->foto_keluar);
            @endphp
            <img src="{{ url($path_masuk) }}" alt="image" class="image">
            <div class="in">
                <div>
                    {{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}
                    <br>
                </div>
                <span class="badge {{ $item->jam_masuk < "09:00" ? "badge-success" : "badge-warning"}}">{{ $item->jam_masuk < "09:00" ? $item->jam_masuk : "Telat " . $item->jam_masuk}}</span>

                <span class="badge badge-danger">{{ $item->jam_keluar}}
            </div>
        </div>
    </li>
</ul>
    {{-- <p>{{ $item->tanggal_presensi }}</p> --}}
@endforeach
