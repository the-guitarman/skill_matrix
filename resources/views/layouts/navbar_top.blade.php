<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
      <a class="navbar-brand" href="{{ route('root') }}">
        <!--<img src="/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">-->
        {{ $app_name }}
    </a>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbarNav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Navigation anzeigen/ausblenden">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse navbarNav">
    <div class="navbar-nav">
      @yield('top_navbar_navigation_button')
      @yield('top_navbar_create_button')
    </div>
  </div>

  <div class="collapse navbar-collapse navbarNav justify-content-end">
    <div class="navbar-nav">
        @unless(Auth::check())
            <a class="nav-item nav-link {{ Route::currentRouteName() !== 'login' ?: 'active' }}" href="{{route('login')}}"><i class="fa fa-sign-in"></i> Login <span class="sr-only">(current)</span></a>
        @else
            @unless(starts_with(Route::currentRouteName(), 'skill-groups.'))
              <a class="nav-item nav-link" href="{{route('skill-groups.index')}}"><i class="fa fa-object-group"></i> Skill-Groups <span class="sr-only">(current)</span></a>
            @endunless
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->login }}</a>
                <div class="dropdown-menu dropdown-menu-right" area-labelledby="navbarDropdownMenuLink">

                  <form action="{{ route('logout') }}" class="link" method="post">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <a class="dropdown-item" data-submit="parent" href="#" rel="nofollow"><i class="fa fa-sign-out"></i> Logout</a>
                  </form>
                </div>
            </div>
        @endunless
    </div>
  </div>
</nav>