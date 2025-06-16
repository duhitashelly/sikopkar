<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:rgb(46, 26, 81);">
    <!-- Tambahkan CSS langsung di sini -->
    <style>
        /* Mengatur hover untuk item yang aktif */
        .sidebar .nav-item.active .nav-link {
            background: linear-gradient(to left, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            font-weight: bold;
        }
        .sidebar .nav-item .nav-link.active {
            background: linear-gradient(to left, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            font-weight: bold;
        }
        .sidebar .nav-item .nav-link:hover {
            background: linear-gradient(to left, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            color: #fff;
            padding-left: 22px;
        }

        /* Ikon dan teks submenu */
        .sidebar .nav-item .nav-link i {
            margin-right: 10px;
            margin-left: 0;
            color: #fff;
            opacity: 0.8;
            transition: margin-left 0.3s;
        }

        .nav-link 
        /* Hover efek hanya untuk item non-aktif */
        .sidebar .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Menghapus efek bold pada item yang tidak aktif */
        .sidebar .nav-item .nav-link {
            font-weight: normal; 
        }

        /* Menggeser ikon dan teks di setiap nav item */
        .sidebar .nav-item .nav-link i, 
        .sidebar .nav-item .nav-link span {
            margin-left: 20px;
        }

        .sidebar .nav-item ul {
        list-style: none;
        padding-left: 10px;
    }

    .sidebar .nav-item ul li a {
        font-size: 14px;
        padding: 5px 0;
        display: block;
    }

    .sidebar .nav-item ul li.active a {
        color: #fff;
        font-weight: bold;
    }

    .sidebar .nav-item ul li a:hover {
        color: #fff;
    }

    /* Hanya link yang aktif yang memiliki background dan bold */
    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.3);
        font-weight: bold;
    }

    /* Hover hanya untuk link yang aktif */
    .sidebar .nav-link.active:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    /* Untuk link yang tidak aktif, hover hanya background ringan */
    .sidebar .nav-link:not(.active):hover {
        background-color: rgba(255, 255, 255, 0.1);
        font-weight: normal;
    }

    /* Pastikan font normal untuk link non aktif */
    .sidebar .nav-link:not(.active) {
        font-weight: normal;
    }

    /* Jika ingin ikon dan teks sedikit bergeser */
    .sidebar .nav-link i,
    .sidebar .nav-link span {
        margin-left: 20px;
    }

        
        
    
    </style>

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon ">
            <i class="fas fa-university"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SI KOPKAR <sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Sidebar Menu for Admin -->
    @if(auth()->check() && auth()->user()->isAdmin())
        <!-- Nav Item - Beranda -->
        <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="/home" data-turbo-frame="main-content-frame">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </li>

        <!-- Nav Item - Anggota -->
        <li class="nav-item {{ Request::is('anggota') ? 'active' : '' }}">
            <a class="nav-link" href="/anggota" data-turbo-frame="main-content-frame">
                <i class="fas fa-users"></i>
                <span>Anggota</span>
            </a>
        </li>

        <!-- Nav Item - Pengguna -->
        <li class="nav-item {{ Request::is('pengguna') ? 'active' : '' }}">
            <a class="nav-link" href="/pengguna" data-turbo-frame="main-content-frame">
                <i class="fas fa-user"></i>
                <span  style="margin-left: 24px;" >Pengguna</span>
            </a>
        </li>

        <!-- Nav Item - Simpanan -->
        <li class="nav-item {{ Request::is('simpanan') ? 'active' : '' }}">
            <a class="nav-link" href="/simpanan" data-turbo-frame="main-content-frame">
                <i class="fas fa-folder-open"></i>
                <span>Simpanan</span>
            </a>
        </li>

        <!-- Nav Item - Pinjaman -->
        <li class="nav-item {{ Request::is('pinjaman') ? 'active' : '' }}">
            <a class="nav-link" href="/pinjaman" data-turbo-frame="main-content-frame">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Pinjaman</span>
            </a>
        </li>

        <!-- Nav Item - Angsuran -->
        <li class="nav-item {{ Request::is('angsuran') ? 'active' : '' }}">
            <a class="nav-link" href="/angsuran" data-turbo-frame="main-content-frame">
                <i class="fas fa-money-bill"></i>
                <span style="margin-left: 20px;">Angsuran</span>
            </a>
        </li>


    <!-- Sidebar Laporan -->
    <li class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
            aria-expanded="{{ Request::is('laporan*') ? 'true' : 'false' }}" aria-controls="collapseLaporan">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseLaporan" class="collapse {{ Request::is('laporan*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-transparent pl-4">
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/anggota') ? 'active' : '' }}" href="/laporan/anggota" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-user-friends mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Anggota</span>
                </a>
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/simpanan') ? 'active' : '' }}" href="/laporan/simpanan" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-piggy-bank mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Simpanan</span>
                </a>
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/pinjaman') ? 'active' : '' }}" href="/laporan/pinjaman" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-hand-holding-usd mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Pinjaman</span>
                </a>               
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/bunga') ? 'active' : '' }}" href="/laporan/bunga" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-dollar-sign mr-2" style="margin-left: -4px;"></i>
                    <span>Laporan Pendapatan Bunga</span>
                </a>
            </div>
        </div>
    </li>



    @elseif(auth()->check() && auth()->user()->isKepalaKoperasi())
        <!-- Nav Item - Beranda -->
        <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="/home">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </li>

    <!-- Sidebar Laporan -->
    <li class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
            aria-expanded="{{ Request::is('laporan*') ? 'true' : 'false' }}" aria-controls="collapseLaporan">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseLaporan" class="collapse {{ Request::is('laporan*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-transparent pl-4">
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/anggota') ? 'active' : '' }}" href="/laporan/anggota" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-user-friends mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Anggota</span>
                </a>
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/simpanan') ? 'active' : '' }}" href="/laporan/simpanan" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-piggy-bank mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Simpanan</span>
                </a>
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/pinjaman') ? 'active' : '' }}" href="/laporan/pinjaman" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-hand-holding-usd mr-2" style="margin-left: -10px;"></i>
                    <span>Laporan Pinjaman</span>
                </a>               
                <a class="nav-link d-flex align-items-center {{ Request::is('laporan/bunga') ? 'active' : '' }}" href="/laporan/bunga" style="font-size: 13px; margin-left: -10px;">
                    <i class="fas fa-dollar-sign mr-2" style="margin-left: -4px;"></i>
                    <span>Laporan Pendapatan Bunga</span>
                </a>
            </div>
        </div>
    </li>




    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
