namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index()
    {
        $penggajian = Penggajian::with('karyawan')->get();
        return view('penggajian.index', compact('penggajian'));
    }

    public function create()
    {
        $karyawan = Karyawan::all();
        return view('penggajian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required',
            'tanggal_gaji' => 'required|date',
        ]);

        $karyawan = Karyawan::where('nik', $request->nik)->first();
        $gaji = $karyawan->jabatan->gaji->jumlah_gaji;

        // Hitung potongan
        $potongan = $this->hitungPotongan($request->nik, $request->tanggal_gaji);

        // Hitung total gaji
        $totalGaji = $gaji - $potongan;

        Penggajian::create([
            'nik' => $request->nik,
            'gaji' => $gaji,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
            'tanggal_gaji' => $request->tanggal_gaji,
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil ditambahkan.');
    }

    public function show($id)
    {
        $penggajian = Penggajian::with('karyawan')->findOrFail($id);
        return view('penggajian.show', compact('penggajian'));
    }

    public function edit($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $karyawan = Karyawan::all();
        return view('penggajian.edit', compact('penggajian', 'karyawan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik' => 'required',
            'tanggal_gaji' => 'required|date',
        ]);

        $penggajian = Penggajian::findOrFail($id);
        $karyawan = Karyawan::where('nik', $request->nik)->first();
        $gaji = $karyawan->jabatan->gaji->jumlah_gaji;

        // Hitung potongan
        $potongan = $this->hitungPotongan($request->nik, $request->tanggal_gaji);

        // Hitung total gaji
        $totalGaji = $gaji - $potongan;

        $penggajian->update([
            'nik' => $request->nik,
            'gaji' => $gaji,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
            'tanggal_gaji' => $request->tanggal_gaji,
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->delete();

        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil dihapus.');
    }

    private function hitungPotongan($nik, $tanggal_gaji)
    {
        $tanggalMulai = date('Y-m-01', strtotime($tanggal_gaji));
        $tanggalSelesai = date('Y-m-t', strtotime($tanggal_gaji));

        // Hitung jumlah ketidakhadiran
        $jumlahKetidakhadiran = Presensi::where('nik', $nik)
            ->whereBetween('tanggal_presensi', [$tanggalMulai, $tanggalSelesai])
            ->where('status', '!=', 'hadir')
            ->count();

        // Tentukan nilai potongan per hari ketidakhadiran (contoh: 100000 per hari)
        $potonganPerHari = 100000;

        return $jumlahKetidakhadiran * $potonganPerHari;
    }
}
