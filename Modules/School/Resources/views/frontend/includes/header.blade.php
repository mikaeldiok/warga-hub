
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-compact-light" id="ftco-navbar-compact">
  <div class="container styled-position">
    <a class="navbar-brand" href="/">Katasis</a>
    <a class="nav-button ml-auto d-md-block d-lg-none" href="{{ route('frontend.corporations.index') }}"><i class="fa-solid fa-2x fa-home"></i></a></li>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="oi oi-menu"></span> Menu
    </button>
    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto">
        @if(Auth::check())
          <div class="d-md-block d-lg-none nav-link">
            <img class="rounded-circle img-fluid float-left mx-2" src="{{asset(Auth::user()->avatar)}}" alt="Photo" height="30px" width="30px">
            <spans style="font-size:13px">
              {{Auth::user()->email}}
            </span> 
            <ul class="nav-item">
              @if(!Auth::user()->hasRole("user"))
                <li class="nav-item active"><a class="nav-link" href="{{ route('backend.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
              @endif
              <li class="nav-item"><a class="nav-link" href="{{ route('frontend.corporations.index') }}"><i class="fa-solid fa-home"></i> Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('frontend.users.profile', auth()->user()->id) }}"><i class="fa-solid fa-user"></i> Profile</a></li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        @endif
        <li class="nav-item {{Route::currentRouteName() == 'frontend.index' ? 'active' : ''}}"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item {{Route::currentRouteName() == 'frontend.students.index' ? 'active' : ''}}"><a href="{{route('frontend.students.index')}}" class="nav-link">Catalog</a></li>
        <li class="nav-item " title="Bookings"><a href="{{route('frontend.bookings.index')}}" class="nav-link"> <i class="fa-solid fa-book fa-lg mt-1" style= "height:24px;width:24px"></i></a></li>
        @auth
          <li class="dropdown d-none d-lg-block nav-button">
            <div class="dropdown border show rounded bg-light border-primary" style="margin-top: 1px;padding-top: 5px;padding-right: 5px;padding-bottom: 8px;">
              <a class="dropdown-toggle light-link" role="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="rounded-circle img-fluid float-left mx-2" src="{{asset(Auth::user()->avatar)}}" alt="Photo" height="30px" width="30px">
                  <spans style="font-size:13px">
                    {{Auth::user()->email}}
                  </span> 
              </a>

              <div class="dropdown-menu" aria-labelledby="dropdownProfile" style="left:auto;right:0;">
                @if( Auth::user()->can('view_backend'))
                <a class="dropdown-item" href="{{ route('backend.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                @endif
                @if(Auth::user()->hasRole("user") ||  !Auth::user()->can('view_backend'))
                <a class="dropdown-item" href="{{ route('frontend.corporations.index') }}"><i class="fa-solid fa-home"></i> Home</a>
                @endif
                <a class="dropdown-item" href="{{ route('frontend.users.profile', auth()->user()->id) }}"><i class="fa-solid fa-user"></i> Profile</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </div>
          </li>
        @else
          <li class="nav-item"><a href="{{route('login')}}" class="btn btn-sm btn-orange nav-button">log in</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
<!-- END nav -->
