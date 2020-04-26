<!-- ============================================================== -->
<!-- User Profile -->
<!-- ============================================================== -->
<li class="nav-item dropdown u-pro">
    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if (Auth::user())
        <img src="{{Auth::user()->urlfoto}}" alt="user" class="img-circle">
        @endif
        <span class="hidden-md-down">
            @if (Auth::user())
            {{Auth::user()->nama}}  
            @endif  
        <i class="fa fa-angle-down"></i></span> </a>
    <div class="dropdown-menu dropdown-menu-right animated flipInY">
         @if (Auth::user())
         <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
        <!-- text-->
        <div class="dropdown-divider"></div>
        <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
        <!-- text-->
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