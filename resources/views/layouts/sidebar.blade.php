<!-- Sidebar navigation-->
<nav class="sidebar-nav">
    <ul id="sidebarnav">
        <li class="user-pro"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"><span class="hide-menu">Mark Jeckson</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="javascript:void(0)"><i class="ti-user"></i> My Profile</a></li>
                <li><a href="javascript:void(0)"><i class="ti-wallet"></i> My Balance</a></li>
                <li><a href="javascript:void(0)"><i class="ti-email"></i> Inbox</a></li>
                <li><a href="javascript:void(0)"><i class="ti-settings"></i> Account Setting</a></li>
                <li><a href="javascript:void(0)"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
        </li>
        <li class="nav-small-cap">--- OPERATOR</li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Dashboard <span class="badge badge-pill badge-cyan ml-auto">4</span></span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{url('')}}">Depan </a></li>
            </ul>
        </li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Kegiatan</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{route('kegiatan.list')}}">Semua</a></li>
                <li><a href="{{route('kegiatan.bidang')}}">Bidang/Bagian</a></li>
            </ul>
        </li>
        <li> <a class="has-arrow waves-effect waves-dark two-column" href="javascript:void(0)" aria-expanded="false"><i class="ti-palette"></i><span class="hide-menu">Peringkat dan Nilai <span class="badge badge-pill badge-primary text-white ml-auto">25</span></span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="#">Menurut Kegiatan</a></li>
            </ul>
        </li>
        <li class="nav-small-cap">--- LAPORAN</li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-media-right-alt"></i><span class="hide-menu">Laporan</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="form-basic.html">Bulanan</a></li>
                <li><a href="form-layout.html">Tahunan</a></li>
            </ul>
        </li>
        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-settings"></i><span class="hide-menu">Master</span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{route('pegawai.list')}}">Pegawai</a></li>
                <li><a href="widget-apps.html">Unitkerja</a></li>
                <li><a href="widget-charts.html">Charts Widgets</a></li>
            </ul>
        </li>
        
        
    </ul>
</nav>
<!-- End Sidebar navigation -->