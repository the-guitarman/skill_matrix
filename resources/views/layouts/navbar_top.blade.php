<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
      <a class="navbar-brand" href="{{ route('root') }}">
        <!--<img src="/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">-->
        {{ $app_name }}
    </a>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <div class="navbar-nav">
        @unless(Auth::check())
            @if(Route::currentRouteName() === 'login')
                <a class="nav-item nav-link active" href="{{route('login')}}">Login <span class="sr-only">(current)</span></a>
            @else
                <a class="nav-item nav-link" href="{{route('login')}}">Login <span class="sr-only">(current)</span></a>
            @endif
        @else
            <a class="nav-item nav-link active" href="{{route('login')}}">Skill-Groups <span class="sr-only">(current)</span></a>
            <div class="nav-item">
                <span class="nav-link disabled navbar-text">Angemeldet: {{ Auth::user()->login }}</span>
            </div>
            <form action="{{ route('logout') }}" class="link" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <a class="nav-link" data-submit="parent" href="#" rel="nofollow">Logout</a>
            </form>
        @endif
    </div>
  </div>
</nav>