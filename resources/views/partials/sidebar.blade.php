<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            <h6>
                Avsec <br><span>Bandara Jalaludin Gorontalo</span>
            </h6>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        @if (Auth::user()->role->name === 'Admin')
            <ul class="nav">
                <li class="nav-item nav-category">Main</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item nav-category">Menu Pegawai</li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Data Pegawai</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#uiCompo" role="button" aria-expanded="false"
                        aria-controls="uiCompo">
                        <i class="link-icon" data-feather="map"></i>
                        <span class="link-title">Lisensi</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse" id="uiCompo">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('verification-license') }}" class="nav-link">Data Lisensi</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('licenses.index') }}" class="nav-link">Pengajuan Lisensi</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button"
                        aria-expanded="false" aria-controls="uiComponents">
                        <i class="link-icon" data-feather="file"></i>
                        <span class="link-title">Rekuren</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse" id="uiComponents">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('rekuren.index') }}" class="nav-link">Pengajuan Rekuren</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rekuren.indexSchedule') }}" class="nav-link">Jadwal Rekuren</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rekuren.indexRekurenHistory') }}" class="nav-link">Riwayat
                                    Rekuren</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#uiComponent" role="button"
                        aria-expanded="false" aria-controls="uiComponent">
                        <i class="link-icon" data-feather="briefcase"></i>
                        <span class="link-title">Diklat</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse" id="uiComponent">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('diklat.index') }}" class="nav-link">Tambah Diklat</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('diklat.indexSchedule') }}" class="nav-link">Jadwal Diklat</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('diklat.indexHistory') }}" class="nav-link">Riwayat Diklat</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        @else
            <ul class="nav">
                <li class="nav-item nav-category">Pegawai</li>
                <li class="nav-item">
                    <a href="{{ route('pegawais.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Profile</span>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</nav>
