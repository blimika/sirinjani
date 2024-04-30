<!-- Sidebar navigation-->
<nav class="sidebar-nav">
    <ul id="sidebarnav">
        <li class="user-pro">
            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <span class="hide-menu">
                @if (Auth::user())
                {{Auth::user()->nama}}
                @else
                MASUK
                @endif &nbsp;</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @if (Auth::user())
                <li><a href="{{route('notif.list')}}"><i class="ti-email"></i> Notifikasi</a></li>
                <li><a href="{{route('my.profile')}}"><i class="ti-user"></i> Profilku</a></li>
                <li><a href="{{route('logout')}}"><i class="fa fa-power-off"></i> Logout</a></li>
                @else
                <li><a href="{{route('login')}}"><i class="ti-user"></i> Login</a></li>
                @endif
            </ul>
        </li>
        <li class="nav-small-cap">--- OPERATOR</li>
        <li> <a class="waves-effect waves-dark" href="{{url('')}}" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Dashboard</a>

        </li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Kegiatan</span></a>
            <ul aria-expanded="false" class="collapse">
                @if (Auth::user())
                    @if (Auth::user()->level == 3 or Auth::user()->level > 4)
                        <li><a href="{{route('kegiatan.tambah')}}">Tambah</a></li>
                    @endif
                @endif
                <li><a href="{{route('kegiatan.list')}}">Semua</a></li>
            </ul>
        </li>
        @if (Auth::user())
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-palette"></i><span class="hide-menu">Peringkat dan Nilai</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{route('peringkat.bulanan')}}">Peringkat Bulanan</a></li>
                <li><a href="{{route('peringkat.tahunan')}}">Peringkat Tahunan</a></li>
                <li><a href="{{route('peringkat.rincian')}}">Rincian Perkabkota</a></li>
                @if (Auth::user())
                    @if (Auth::user()->flag_liatckp == 1 or Auth::user()->level > 5)
                        <li><a href="{{route('peringkat.ckp')}}">Rekap Nilai CKP</a></li>
                    @endif

                    @if (Auth::user()->level == 3 or Auth::user()->level > 4)
                        <li><a href="{{route('peringkat.rekapnilai')}}">Rekap Nilai Bulanan</a></li>
                    @endif
                @endif

            </ul>
        </li>
        <li class="nav-small-cap">--- LAPORAN</li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-media-right-alt"></i><span class="hide-menu">Laporan</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{route('laporan.bulanan')}}">Bulanan</a></li>
                <li><a href="{{route('laporan.tahunan')}}">Tahunan</a></li>
            </ul>
        </li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-settings"></i><span class="hide-menu">Master</span></a>
            <ul aria-expanded="false" class="collapse">
                    <li><a href="{{route('operator.list')}}">Operator</a></li>
                @if (Auth::user()->level == 9)
                <li><a href="javascript:void(0)" class="has-arrow">Unitkerja</a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{route('unitkerja.prov')}}"><i class="ti-angle-double-right"></i> Provinsi</a></li>
                        <li><a href="{{route('unitkerja.kabkota')}}"><i class="ti-angle-double-right"></i> Kabkota</a></li>
                    </ul>
                </li>
                    <li><a href="{{route('bot.telegram')}}">Bot Telegram</a></li>
                    <li><a href="{{route('aktivitas.list')}}">Log Aktivitas</a></li>
                    <!--<li><a href="{{route('poin.list')}}">Rekap Poin</a></li>-->
                    <li><a href="{{route('master.kegiatan')}}">Kegiatan</a></li>
                    <!--<li><a href="{{route('db.index')}}">Database</a></li>-->
                @endif
            </ul>
        </li>
        @endif
    </ul>
</nav>
<!-- End Sidebar navigation -->
