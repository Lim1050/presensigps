<div class="preview-gaji">
    <h3>Preview Slip Gaji</h3>

    <!-- Data Karyawan -->
    <div class="data-karyawan mb-4">
        <h4>Data Karyawan</h4>
        <p><strong>NIK:</strong> {{ $karyawan->nik }}</p>
        <p><strong>Nama:</strong> {{ $karyawan->nama_lengkap }}</p>
        <p><strong>Jabatan:</strong> {{ $karyawan->jabatan->nama_jabatan }}</p>
        <p><strong>Kantor Cabang:</strong> {{ $karyawan->Cabang->nama_cabang }}</p>
        <p><strong>Lokasi Penugasan:</strong> {{ $karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</p>
    </div>

    <!-- Rincian Kehadiran -->
    <div class="kehadiran mb-4">
        <h4>Data Kehadiran</h4>
        <ul>
            <li>Jumlah Hari Masuk: {{ $totalKehadiran }} hari</li>
            <li>Jumlah Ketidakhadiran: {{ $totalKetidakhadiran }} hari</li>
            <li>Total Jam Lembur: {{ number_format($lembur / 60, 2) }} jam</li>
        </ul>
    </div>

    <!-- Komponen Penghasilan -->
    <div class="penghasilan mb-4">
        <h4>Komponen Penghasilan</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponenGaji as $nama => $nominal)
                <tr>
                    <td>{{ $nama }}</td>
                    <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="font-weight-bold">
                    <td>Total Penghasilan</td>
                    <td class="text-right">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Komponen Potongan -->
    <div class="potongan mb-4">
        <h4>Komponen Potongan</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponenPotongan as $nama => $nominal)
                <tr>
                    <td>{{ $nama }}</td>
                    <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="font-weight-bold">
                    <td>Total Potongan</td>
                    <td class="text-right">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Gaji Bersih -->
    <div class="gaji-bersih mb-4">
        <h4>Gaji Bersih</h4>
        <p class="font-weight-bold">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</p>
    </div>
</div>
