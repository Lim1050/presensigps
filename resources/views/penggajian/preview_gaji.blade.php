<div class="preview-gaji">
    <h3>Preview Slip Gaji</h3>

    <!-- Data Karyawan -->
    <div class="data-karyawan mb-4">
        <h4>Data Karyawan</h4>
        <table class="table table-borderless">
            <tr>
                <td width="200">NIK</td>
                <td width="10">:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
            </tr>
            <tr>
                <td>Kantor Cabang</td>
                <td>:</td>
                <td>{{ $karyawan->Cabang->nama_cabang }}</td>
            </tr>
            <tr>
                <td>Lokasi Penugasan</td>
                <td>:</td>
                <td>{{ $karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</td>
            </tr>
            <tr>
                <td>Jumlah Hari Kerja</td>
                <td>:</td>
                <td>{{ $hariKerjaLokasi }} hari</td>
            </tr>
        </table>
    </div>

    <!-- Data Kehadiran -->
    <div class="kehadiran mb-4">
        <h4>Data Kehadiran</h4>
        <table class="table table-borderless">
            <tr>
                <td width="200">Jumlah Hari Masuk</td>
                <td width="10">:</td>
                <td>{{ $totalKehadiran }} hari</td>
            </tr>
            <tr>
                <td>Jumlah Ketidakhadiran</td>
                <td>:</td>
                <td>{{ $totalKetidakhadiran }} hari</td>
            </tr>
            <tr>
                <td>Total Jam Lembur</td>
                <td>:</td>
                <td>{{ number_format($lembur / 60, 2) }} jam</td>
            </tr>
        </table>
    </div>

    <!-- Komponen Penghasilan -->
    <div class="penghasilan mb-4">
        <h4>Komponen Penghasilan</h4>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah Awal</th>
                    <th class="text-right">Potongan</th>
                    <th class="text-right">Jumlah Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponenGajiArray as $kode => $data)
                    @if($kode !== 'L')
                        <tr>
                            <td>{{ $data['jenis_gaji'] }}</td>
                            <td class="text-right">Rp {{ number_format($komponenGajiAsli[$kode], 0, ',', '.') }}</td>
                            <td class="text-right">
                                @if($kode === 'GT' && isset($komponenPotongan['Potongan Ketidakhadiran']))
                                    Rp {{ number_format($komponenPotongan['Potongan Ketidakhadiran'], 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">Rp {{ number_format($data['jumlah_gaji'], 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach

                @if(isset($komponenGajiArray['L']) && $lembur > 0)
                    <tr>
                        <td>{{ $komponenGajiArray['L']['jenis_gaji'] }} × {{ number_format($lembur / 60, 2) }} jam</td>
                        <td class="text-right">Rp {{ number_format($komponenGajiAsli['L'], 0, ',', '.') }} (Per jam)</td>
                        <td class="text-right">-</td>
                        <td class="text-right">Rp {{ number_format($komponenGajiArray['L']['jumlah_gaji'], 0, ',', '.') }}</td>
                    </tr>
                @endif

                <tr class="font-weight-bold">
                    <td>Total Penghasilan</td>
                    <td class="text-right">Rp {{ number_format($totalGajiAsli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($komponenPotongan['Potongan Ketidakhadiran'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Komponen Potongan -->
    <div class="potongan mb-4">
        <h4>Rincian Potongan</h4>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Rincian</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponenPotongan as $nama => $nominal)
                    @if($nama === 'Potongan Ketidakhadiran')
                        <tr>
                            <td>{{ $nama }}</td>
                            <td class="text-right">
                                Rp {{ number_format($komponenGajiAsli['GT']/$hariKerjaLokasi, 0, ',', '.') }} × {{ $totalKetidakhadiran }} hari
                            </td>
                            <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $nama }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr class="font-weight-bold">
                    <td colspan="2">Total Potongan</td>
                    <td class="text-right">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Gaji Bersih -->
    <div class="gaji-bersih mb-4">
        <h4>Gaji Bersih</h4>
        <table class="table table-bordered">
            <tr class="font-weight-bold">
                <td>Total Gaji Bersih</td>
                <td class="text-right">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>

<style>
    .preview-gaji {
        padding: 20px;
    }
    .preview-gaji h3 {
        margin-bottom: 20px;
    }
    .preview-gaji h4 {
        margin-bottom: 15px;
        color: #333;
    }
    .table {
        margin-bottom: 0;
    }
    .thead-light th {
        background-color: #f8f9fa;
    }
    .text-right {
        text-align: right;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>