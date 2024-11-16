<ul class="navbar-nav bg-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-person-check-fill"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin E - Presensi</div>
    </a>

    <!-- Divider -->
    @if (Auth::user()->can('dashboard.all'))
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/lembur*', 'admin/persetujuan/sakit/izin*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseOne"
            aria-expanded="{{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/lembur*', 'admin/persetujuan/sakit/izin*']) ? 'true' : 'false' }}" aria-controls="collapseOne">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <div id="collapseOne" class="collapse {{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/lembur*', 'admin/persetujuan/sakit/izin*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                @if (Auth::user()->can('dashboard.dashboard'))
                <a class="collapse-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
                @endif

                @if (Auth::user()->can('dashboard.monitoring-presensi'))
                <a class="collapse-item {{ request()->is('admin/monitoring/presensi*') ? 'active' : '' }}" href="{{ route('admin.monitoring.presensi') }}">
                <i class="bi bi-display fa-fw "></i>
                <span>Monitoring Presensi</span></a>
                @endif

                @if (Auth::user()->can('dashboard.lembur-karyawan'))
                <a class="collapse-item {{ request()->is('admin/lembur*') ? 'active' : '' }}" href="{{ route('admin.lembur') }}">
                <i class="bi bi-clock-history fa-fw"></i>
                <span>Lembur Karyawan</span></a>
                @endif

                @if (Auth::user()->can('dashboard.persetujuan-sakit-izin'))
                <a class="collapse-item {{ request()->is('admin/persetujuan/sakit/izin*') ? 'active' : '' }}" href="{{ route('admin.persetujuan.sakit.izin') }}">
                <i class="bi bi-file-text-fill fa-fw "></i>
                <span>Persetujuan Sakit / Izin</span></a>
                @endif
            </div>
        </div>
    </li>
    @endif


    <!-- Divider -->
    @if (Auth::user()->can('laporan.all'))
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="{{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'true' : 'false' }}" aria-controls="collapseTwo">
            <i class="bi bi-file-earmark fa-fw"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseTwo" class="collapse {{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'show' : '' }} bg-red" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                @if (Auth::user()->can('laporan.laporan-presensi'))
                <a class="collapse-item {{ request()->is('admin/laporan/presensi*') ? 'active' : '' }}" href="{{ route('admin.laporan.presensi') }}">
                <i class="bi bi-file-earmark-text fa-fw"></i>
                <span>Laporan Presensi</span></a>
                @endif

                @if (Auth::user()->can('laporan.rekap-presensi'))
                <a class="collapse-item {{ request()->is('admin/rekap/presensi*') ? 'active' : '' }}" href="{{ route('admin.rekap.presensi') }}">
                <i class="bi bi-file-earmark-spreadsheet fa-fw"></i>
                <span>Rekap Presensi</span></a>
                @endif
            </div>
        </div>
    </li>
    @endif

    <!-- Divider -->
    @if (Auth::user()->can('keuangan.all'))
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/penggajian*', 'admin/cashbon*', 'admin/thr*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapsePenggajian"
            aria-expanded="{{ request()->is(['admin/penggajian*', 'admin/cashbon*', 'admin/thr*']) ? 'true' : 'false' }}" aria-controls="collapsePenggajian">
            <i class="bi bi-file-earmark fa-fw"></i>
            <span>Keuangan</span>
        </a>
        <div id="collapsePenggajian" class="collapse {{ request()->is(['admin/penggajian*', 'admin/cashbon*', 'admin/thr*']) ? 'show' : '' }} bg-red" aria-labelledby="headingPenggajian" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                @if (Auth::user()->can('keuangan.penggajian-karyawan'))
                <a class="collapse-item {{ request()->is('admin/penggajian*') ? 'active' : '' }}" href="{{ route('admin.penggajian') }}">
                    <i class="bi bi-file-earmark-text fa-fw"></i>
                    <span>Penggajian Karyawan</span>
                </a>
                @endif

                @if (Auth::user()->can('keuangan.cashbon-karyawan'))
                <a class="collapse-item {{ request()->is('admin/cashbon*') ? 'active' : '' }}" href="{{ route('admin.cashbon') }}">
                    <i class="bi bi-file-earmark-text fa-fw"></i>
                    <span>Cashbon Karyawan</span>
                </a>
                @endif

                @if (Auth::user()->can('keuangan.thr-karyawan'))
                <a class="collapse-item {{ request()->is('admin/thr*') ? 'active' : '' }}" href="{{ route('admin.thr') }}">
                    <i class="bi bi-file-earmark-text fa-fw"></i>
                    <span>THR Karyawan</span>
                </a>
                @endif
            </div>
        </div>
    </li>
    @endif

    <!-- Divider -->
    @if (Auth::user()->can('master.all'))
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/lokasi/penugasan*', 'admin/cabang*', 'admin/cuti*', 'admin/jabatan*', 'admin/gaji*', 'admin/potongan*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="{{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/lokasi/penugasan*', 'admin/cabang*', 'admin/cuti*', 'admin/jabatan*', 'admin/gaji*', 'admin/potongan*']) ? 'true' : 'false' }}" aria-controls="collapseThree">
            <i class="bi bi-archive fa-fw"></i>
            <span>Master</span>
        </a>
        <div id="collapseThree" class="collapse bg-red {{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/lokasi/penugasan*', 'admin/cabang*', 'admin/cuti*', 'admin/jabatan*', 'admin/gaji*', 'admin/potongan*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                @if (Auth::user()->can('master.karyawan'))
                <a class="collapse-item {{ request()->is('admin/karyawan*') ? 'active' : '' }}" href="{{ route('admin.karyawan') }}">
                <i class="bi bi-person fa-fw"></i>
                <span>Karyawan</span></a>
                @endif

                @if (Auth::user()->can('master.jabatan'))
                <a class="collapse-item {{ request()->is('admin/jabatan*') ? 'active' : '' }}" href="{{ route('admin.jabatan') }}">
                <i class="bi bi-person-vcard fa-fw"></i>
                <span>Jabatan</span></a>
                @endif

                @if (Auth::user()->can('master.departemen'))
                <a class="collapse-item {{ request()->is('admin/departemen*') ? 'active' : '' }}" href="{{ route('admin.departemen') }}">
                <i class="bi bi-building fa-fw"></i>
                <span>Departemen</span></a>
                @endif

                @if (Auth::user()->can('master.lokasi-penugasan'))
                <a class="collapse-item {{ request()->is('admin/lokasi/penugasan*') ? 'active' : '' }}" href="{{ route('admin.lokasi.penugasan') }}">
                <i class="bi bi-pin-map-fill fa-fw"></i>
                <span>Lokasi Penugasan</span></a>
                @endif

                @if (Auth::user()->can('master.kantor-cabang'))
                <a class="collapse-item {{ request()->is('admin/cabang*') ? 'active' : '' }}" href="{{ route('admin.cabang') }}">
                <i class="bi bi-buildings fa-fw"></i>
                <span>Kantor Cabang</span></a>
                @endif

                @if (Auth::user()->can('master.cuti'))
                <a class="collapse-item {{ request()->is('admin/cuti*') ? 'active' : '' }}" href="{{ route('admin.cuti') }}">
                <i class="bi bi-calendar3 fa-fw"></i>
                <span>Cuti</span></a>
                @endif

                @if (Auth::user()->can('master.gaji'))
                <a class="collapse-item {{ request()->is('admin/gaji*') ? 'active' : '' }}" href="{{ route('admin.gaji') }}">
                <i class="bi bi-cash fa-fw"></i>
                <span>Gaji</span></a>
                @endif

                @if (Auth::user()->can('master.potongan'))
                <a class="collapse-item {{ request()->is('admin/potongan*') ? 'active' : '' }}" href="{{ route('admin.potongan') }}">
                <i class="bi bi-cash fa-fw"></i>
                <span>Potongan</span></a>
                @endif
            </div>
        </div>
    </li>
    @endif

    @if (Auth::user()->can('konfigurasi.all'))
    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/konfigurasi/jenis/gaji*', 'admin/konfigurasi/jenis/potongan*', 'admin/konfigurasi/cashbon/limit*', 'admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja/lokasi-penugasan*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*', 'admin/konfigurasi/role*', 'admin/konfigurasi/permission*', 'admin/konfigurasi/add-role-in-permission*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="{{ request()->is(['admin/konfigurasi/jenis/gaji*', 'admin/konfigurasi/jenis/potongan*', 'admin/konfigurasi/cashbon/limit*', 'admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja/lokasi-penugasan*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*', 'admin/konfigurasi/role*', 'admin/konfigurasi/permission*', 'admin/konfigurasi/add-role-in-permission*']) ? 'true' : 'false' }}" aria-controls="collapseFour">
            <i class="bi bi-gear fa-fw"></i>
            <span>Konfigurasi</span>
        </a>
        <div id="collapseFour" class="collapse bg-red {{ request()->is(['admin/konfigurasi/jenis/gaji*', 'admin/konfigurasi/jenis/potongan*', 'admin/konfigurasi/cashbon/limit*', 'admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja/lokasi-penugasan*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*', 'admin/konfigurasi/role*', 'admin/konfigurasi/permission*', 'admin/konfigurasi/add-role-in-permission*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                @if (Auth::user()->can('konfigurasi.jenis-gaji'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jenis/gaji*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jenis.gaji') }}">
                <i class="bi bi-cash fa-fw"></i>
                <span>Jenis Gaji</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.jenis-potongan'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jenis/potongan*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jenis.potongan') }}">
                <i class="bi bi-cash fa-fw"></i>
                <span>Jenis Potongan</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.limit-cashbon'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/cashbon/limit*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.cashbon.limit') }}">
                <i class="bi bi-cash fa-fw"></i>
                <span>Limit Cashbon</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.jam-kerja'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jam/kerja*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jam.kerja') }}">
                <i class="bi bi-clock fa-fw"></i>
                <span>Jam Kerja</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.jam-kerja-departemen'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jam-kerja-dept*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jam-kerja-dept') }}">
                <i class="bi bi-clock fa-fw"></i>
                <span>Jam Kerja Departemen</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.user'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/user*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.user') }}">
                <i class="bi bi-person-fill fa-fw"></i>
                <span>User</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.role'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/role*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.role') }}">
                <i class="bi bi-universal-access"></i>
                <span>Role</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.permission'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/permission*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.permission') }}">
                <i class="bi bi-universal-access-circle"></i>
                <span>Permission</span></a>
                @endif

                @if (Auth::user()->can('konfigurasi.role-in-permission'))
                <a class="collapse-item {{ request()->is('admin/konfigurasi/add-role-in-permission*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.add-role-in-permission') }}">
                <i class="bi bi-ui-checks-grid"></i>
                <span>Role in Permission</span></a>
                @endif
            </div>
        </div>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Karyawan -->
    <li class="nav-item">
        <a class="nav-link logout-confirm" href="{{ route('admin.logout') }}">
            <i class="bi bi-box-arrow-left fa-fw"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
@push('myscript')



@endpush
