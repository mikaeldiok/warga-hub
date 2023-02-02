
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
  <div class="container">
    <a class="navbar-brand" href="/">MK STARTER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="oi oi-menu"></span> Menu
    </button>
    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto">
        @if(Auth::check())
          <div class="d-md-block d-lg-none nav-link">
            <img class="rounded-circle img-fluid float-left mx-2" src="{{asset(Auth::user()->avatar)}}" alt="Photo" height="30px" width="30px">
            <spans style="font-size:20px">
              Hi, {{Auth::user()->first_name}} !
            </span> 
            <ul class="nav-item">
              @if(!Auth::user()->hasRole("user"))
                <li class="nav-item active"><a class="nav-link" href="{{ route('backend.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
              @endif
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
        <li class="nav-item {{Route::currentRouteName() == 'frontend.page.about' ? 'active' : ''}}"><a href="{{route('frontend.page.about')}}" class="nav-link">About</a></li>
        <li class="nav-item {{Route::currentRouteName() == 'frontend.page.gallery' ? 'active' : ''}}"><a href="{{route('frontend.page.gallery')}}" class="nav-link">Gallery</a></li>
        @auth
          @if(Auth::user()->hasRole("user") ||  !Auth::user()->can('view_backend'))
            <!-- user only -->
          @endif
          <li class="dropdown d-none d-lg-block nav-button">
            <div class="dropdown show">
              <a class="dropdown-toggle nav-button" role="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="rounded-circle img-fluid float-left mx-2" src="{{asset(Auth::user()->avatar)}}" alt="Photo" height="30px" width="30px">
                  <spans style="font-size:20px">
                    Hi, {{Str::limit(Auth::user()->first_name,12)}} !
                  </span> 
              </a>

              <div class="dropdown-menu" aria-labelledby="dropdownProfile" style="left:auto;right:0;">
                @if( Auth::user()->can('view_backend'))
                <a class="dropdown-item" href="{{ route('backend.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
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
