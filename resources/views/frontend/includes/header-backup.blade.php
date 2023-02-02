<header class="header-global">
    <nav id="navbar-main" class="navbar warga-purple navbar-main navbar-expand-lg warga-purple headroom py-lg-3 px-lg-6 navbar-dark navbar-theme-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img class="navbar-brand-dark common" src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo light">
                <img class="navbar-brand-light common" src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo dark">
            </a>
            <div class="navbar-collapse collapse" id="navbar_global">
                <div class="navbar-collapse-header">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="/">
                                <img src="{{asset('img/backend-logo.jpg')}}" height="35" alt="Logo Impact">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <a href="#navbar_global" role="button" class="fas fa-times" data-toggle="collapse"
                                data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false"
                                aria-label="Toggle navigation"></a>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav navbar-nav-hover justify-content-center">
                    @if(!Auth::guard('web')->check())
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" aria-expanded="false" data-toggle="dropdown">
                                <span class="nav-link-inner-text mr-1">
                                    <span class="fas fa-user-tie mr-1"></span>
                                    Donatur
                                </span>
                                <i class="fas fa-angle-down nav-link-arrow"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg">
                                <div class="col-auto px-0" data-dropdown-content>
                                    <div class="list-group list-group-flush">
                                        @auth('donator')
                                        <a href="{{ route('frontend.donators.index') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-success"><i class="fas fa-house-user"></i></span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    Donator Area
                                                </span>
                                                <span class="small">Masuk ke area donator</span>
                                            </div>
                                        </a>
                                        <a href="{{ route('auth.donators.logout') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">
                                            <span class="icon icon-sm icon-secondary">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    Logout
                                                </span>
                                                <span class="small">Logout from your account!</span>
                                            </div>
                                        </a>
                                        <form id="account-logout-form" action="{{ route('auth.donators.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @else
                                        <a href="{{ route('auth.donators.login') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-secondary"><i class="fas fa-key"></i></span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    Login
                                                </span>
                                                <span class="small">Login e Area Donatur</span>
                                            </div>
                                        </a>
                                        <a href="{{ route('auth.donators.register') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-primary">
                                                <i class="fas fa-address-card"></i>
                                            </span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">Daftar</span>
                                                <span class="small">Yuk bergabung bersama kami</span>
                                            </div>
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if(!Auth::guard('donator')->check())
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" aria-expanded="false" data-toggle="dropdown">
                                <span class="nav-link-inner-text mr-1">
                                    <span class="fas fa-user mr-1"></span>
                                    Admin
                                </span>
                                <i class="fas fa-angle-down nav-link-arrow"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg">
                                <div class="col-auto px-0" data-dropdown-content>
                                    <div class="list-group list-group-flush">
                                        @auth
                                        <a href="{{ route('frontend.users.profile', auth()->user()->id) }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-success"><i class="fas fa-user"></i></span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    {{ Auth::user()->name }}
                                                </span>
                                                <span class="small">View profile details!</span>
                                            </div>
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">
                                            <span class="icon icon-sm icon-secondary">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    Logout
                                                </span>
                                                <span class="small">Logout from your account!</span>
                                            </div>
                                        </a>
                                        <form id="account-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-secondary"><i class="fas fa-key"></i></span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">
                                                    Login
                                                </span>
                                                <span class="small">Login to the application</span>
                                            </div>
                                        </a>
                                        @if(user_registration())
                                        <a href="{{ route('register') }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                                            <span class="icon icon-sm icon-primary">
                                                <i class="fas fa-address-card"></i>
                                            </span>
                                            <div class="ml-4">
                                                <span class="text-dark d-block">Register</span>
                                                <span class="small">Join with us!</span>
                                            </div>
                                        </a>
                                        @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="d-none d-lg-block">
                @can('view_backend')
                <a href="{{ route('backend.dashboard') }}" class="btn btn-white animate-up-2 mr-3"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                @endcan
            </div>
            <!-- on small and xtrasmall -->
            <div class="d-lg-none d-md-none d-sm-block d-xs-block">
                @can('view_backend')
                <a href="{{ route('backend.dashboard') }}" class="btn btn-white"><i class="fas fa-tachometer-alt"></i></a>
                @endcan
            </div>
            <div class="d-flex d-lg-none align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            </div>
        </div>
    </nav>
</header>
