{{-- Action List Button --}}
<ul class="action-button-list">
    <li>
        @if ($data_izin->status == "izin")
        <a href="{{ route('izin.absen.edit', $data_izin->kode_izin) }}"  class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Ubah
            </span>
        </a>
        @elseif ($data_izin->status == "sakit")
        <a href="{{ route('izin.sakit.edit', $data_izin->kode_izin) }}"  class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Ubah
            </span>
        </a>
        @elseif ($data_izin->status == "cuti")
        <a href="{{ route('izin.cuti.edit', $data_izin->kode_izin) }}"  class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Ubah
            </span>
        </a>
        @endif

    </li>
    <li>
        <a href="#" id="deletebutton" class="btn btn-list text-danger" data-dismiss="modal" data-toggle="modal" data-target="#deleteConfirm">
            <span>
                <ion-icon name="trash-outline"></ion-icon>
                Hapus
            </span>
        </a>
    </li>
</ul>

<script>
    $(function() {
        $("#deletebutton").click(function(e){
            $("#hapuspengajuan").attr('href', '/izin/delete/' + '{{ $data_izin->kode_izin }}');
        });
    });
</script>
