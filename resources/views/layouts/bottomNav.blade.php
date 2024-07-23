<style>
    .appBottomMenu .item.active ion-icon, .appBottomMenu .item.active strong {
    color: #ca364b !important;
}
</style>
<div class="appBottomMenu">
    <a href="{{ route('dashboard') }}" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('presensi.history') }}" class="item {{ request()->is('presensi/history') ? 'active' : '' }}" >
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>
    <a href="{{ route('presensi.create') }}" class="item">
        <div class="col">
            <div class="action-button large bg-danger">
                <ion-icon name="camera"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('izin') }}" class="item {{ request()->is('izin') ? 'active' : '' }}" >
        <div class="col">
            <ion-icon name="reader-outline"></ion-icon>
            <strong>Izin/Sakit/Cuti</strong>
        </div>
    </a>
    <a href="{{ route('profile') }}" class="item {{ request()->is('profile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="person-outline"></ion-icon>
            <strong>Profil</strong>
        </div>
    </a>
</div>
