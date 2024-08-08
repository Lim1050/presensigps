<ul class="navbar-nav bg-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-person-check-fill"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin E - Presensi</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/persetujuan/sakit/izin*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseOne"
            aria-expanded="{{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/persetujuan/sakit/izin*']) ? 'true' : 'false' }}" aria-controls="collapseOne">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <div id="collapseOne" class="collapse {{ request()->is(['admin/dashboard*', 'admin/monitoring/presensi*', 'admin/persetujuan/sakit/izin*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>

                <a class="collapse-item {{ request()->is('admin/monitoring/presensi*') ? 'active' : '' }}" href="{{ route('admin.monitoring.presensi') }}">
                <i class="bi bi-display fa-fw "></i>
                <span>Monitoring Presensi</span></a>

                <a class="collapse-item {{ request()->is('admin/persetujuan/sakit/izin*') ? 'active' : '' }}" href="{{ route('admin.persetujuan.sakit.izin') }}">
                <i class="bi bi-file-text-fill fa-fw "></i>
                <span>Persetujuan Sakit / Izin</span></a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="{{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'true' : 'false' }}" aria-controls="collapseTwo">
            <i class="bi bi-file-earmark fa-fw"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseTwo" class="collapse {{ request()->is(['admin/laporan/presensi*', 'admin/rekap/presensi*']) ? 'show' : '' }} bg-red" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/laporan/presensi*') ? 'active' : '' }}" href="{{ route('admin.laporan.presensi') }}">
                <i class="bi bi-file-earmark-text fa-fw"></i>
                <span>Laporan Presensi</span></a>

                <a class="collapse-item {{ request()->is('admin/rekap/presensi*') ? 'active' : '' }}" href="{{ route('admin.rekap.presensi') }}">
                <i class="bi bi-file-earmark-spreadsheet fa-fw"></i>
                <span>Rekap Presensi</span></a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/cabang*', 'admin/cuti*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="{{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/cabang*', 'admin/cuti*']) ? 'true' : 'false' }}" aria-controls="collapseThree">
            <i class="bi bi-archive fa-fw"></i>
            <span>Master</span>
        </a>
        <div id="collapseThree" class="collapse bg-red {{ request()->is(['admin/karyawan*', 'admin/departemen*', 'admin/cabang*', 'admin/cuti*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/karyawan*') ? 'active' : '' }}" href="{{ route('admin.karyawan') }}">
                <i class="bi bi-person-vcard fa-fw"></i>
                <span>Karyawan</span></a>

                <a class="collapse-item {{ request()->is('admin/departemen*') ? 'active' : '' }}" href="{{ route('admin.departemen') }}">
                <i class="bi bi-building fa-fw"></i>
                <span>Departemen</span></a>

                <a class="collapse-item {{ request()->is('admin/cabang*') ? 'active' : '' }}" href="{{ route('admin.cabang') }}">
                <i class="bi bi-buildings fa-fw"></i>
                <span>Kantor Cabang</span></a>

                <a class="collapse-item {{ request()->is('admin/cuti*') ? 'active' : '' }}" href="{{ route('admin.cuti') }}">
                <i class="bi bi-calendar3 fa-fw"></i>
                <span>Cuti</span></a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is(['admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*']) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="{{ request()->is(['admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*']) ? 'true' : 'false' }}" aria-controls="collapseFour">
            <i class="bi bi-gear fa-fw"></i>
            <span>Konfigurasi</span>
        </a>
        <div id="collapseFour" class="collapse bg-red {{ request()->is(['admin/konfigurasi/jam/kerja*', 'admin/konfigurasi/jam-kerja-dept*', 'admin/konfigurasi/user*']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-danger py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jam/kerja*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jam.kerja') }}">
                <i class="bi bi-clock fa-fw"></i>
                <span>Jam Kerja</span></a>
                <a class="collapse-item {{ request()->is('admin/konfigurasi/jam-kerja-dept*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.jam-kerja-dept') }}">
                <i class="bi bi-clock fa-fw"></i>
                <span>Jam Kerja Departemen</span></a>
                <a class="collapse-item {{ request()->is('admin/konfigurasi/user*') ? 'active' : '' }}" href="{{ route('admin.konfigurasi.user') }}">
                <i class="bi bi-person-fill fa-fw"></i>
                <span>User</span></a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Karyawan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.logout') }}">
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
