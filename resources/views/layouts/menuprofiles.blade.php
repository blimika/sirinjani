<!-- ============================================================== -->
<!-- User Profile -->
<!-- ============================================================== -->
<li class="nav-item dropdown u-pro">
    <a class="nav-link dropdown-toggle waves-effect waves-dark profil" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       <span class="hidden-md-down">
            @if (Auth::user())
            {{Auth::user()->nama}}
            @else
            MASUK
            @endif
            &nbsp;<i class="fa fa-angle-down"></i></span> </a>
    <div class="dropdown-menu dropdown-menu-right animated flipInY">
         @if (Auth::user())
         <!-- text-->
        <a href="{{route('notif.list')}}" class="dropdown-item"><i class="ti-email"></i> Notifikasi</a>
        <div class="dropdown-divider"></div>
        <!-- text-->
        <a href="{{route('my.profile')}}" class="dropdown-item"><i class="ti-user"></i> Profilku</a>
        <div class="dropdown-divider"></div>
        <!-- text-->
        <a href="{{route('logout')}}" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
        @else
        <!-- text-->
        <a href="{{route('login')}}" class="dropdown-item"><i class="ti-user"></i> Login</a>
        @endif
    </div>
</li>
<!-- ============================================================== -->
<!-- End User Profile -->
<!-- ============================================================== -->
