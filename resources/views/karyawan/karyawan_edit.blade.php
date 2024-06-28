<style>
        .preview-container {
            margin-top: 20px;
            text-align: center;
        }
        .preview-container img {
            width: 200px;
            height: 300px;
            object-fit: cover;
            display: none; /* Initially hide the image */
        }
</style>
<form action="karyawan/update/{{ $karyawan->nik }}" method="POST" id="formKaryawan" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-upc-scan"></i>
            <input type="text" class="form-control" id="nik" name="nik" placeholder=" {{ $karyawan->nik }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-person-fill"></i>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder=" {{ $karyawan->nama_lengkap }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-telephone-fill"></i>
            <input type="text" class="form-control" id="no_wa" name="no_wa" placeholder=" {{ $karyawan->no_wa }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-person-vcard"></i>
            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder=" {{ $karyawan->jabatan }}">
        </div>
    </div>
    <div class="form-group">
        <select name="kode_departemen" id="kode_departement" class="form-control">
            <option value="">Pilih Departemen</option>
            @foreach ($departemen as $item)
            <option {{ $karyawan->kode_departemen == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
            @endforeach
        </select>
    </div>
    <div class="input-group mb-3">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="foto" name="foto">
            <label class="custom-file-label" for="foto"  aria-describedby="inputGroupFileAddon02"><i class="bi bi-image"></i> Pilih Foto Karyawan</label>
        </div>
    </div>
    <input type="text" class="form-control" id="foto" name="foto" value="{{ $karyawan->foto }}">
    <div class="preview-container">
        @php
            $path = Storage::url("uploads/karyawan/".$karyawan->foto)
        @endphp
        <img id="imgPreview" src="{{ $path }}" alt="Your Image">
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>
@push('myscript')
<script>

        $(document).ready(function () {
            $('#foto').on('change', function () {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imgPreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imgPreview').hide();
                }
            });
        });

</script>
@endpush
