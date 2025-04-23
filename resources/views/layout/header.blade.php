<nav class="navbar navbar-expand">
    <div class="mobile-toggle-icon d-xl-none">
        <i class="bi bi-list"></i>
    </div>
    <div class="top-navbar d-none d-xl-block">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                @if(auth('admin')->check())
                <a class="nav-link" href="{{route('admin.dashboard')}}">Admin Dashboard</a>
                @elseif(auth('vendor')->check())
                <a class="nav-link" href="{{route('vendor.dashboard')}}">Vendor Dashboard</a>
                @elseif(auth('fieldagent')->check())
                <a class="nav-link" href="{{route('fieldagent.dashboard')}}">Field Agent Dashboard</a>
                @else
                <a class="nav-link" href="##">Dashboard</a>
                @endif
            </li>
        </ul>
    </div>
    <div class="search-toggle-icon d-xl-none ms-auto">
        <i class="bi bi-search"></i>
    </div>
    <div class="top-navbar-right ms-auto">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item dropdown dropdown-large">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                    <div class="user-setting d-flex align-items-center gap-1">
                        <img src="{{asset('public/assets/images/avatars/avatar-1.png')}}" class="user-img" alt="">
                        <div class="user-name d-none d-sm-block">
                            @if(auth('admin')->check())
                            {{ auth('admin')->user()->name }}
                            @elseif(auth('vendor')->check())
                            {{ auth('vendor')->user()->name }}
                            @elseif(auth('fieldagent')->check())
                            {{ auth('fieldagent')->user()->name }}
                            @else
                            Welcome, Guest
                            @endif

                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <img src="{{asset('public/assets/images/avatars/avatar-1.png')}}" alt="" class="rounded-circle" width="60" height="60">
                                <div class="ms-3">
                                    <h6 class="mb-0 dropdown-user-name">
                                        @if(auth('admin')->check())
                                        {{ auth('admin')->user()->name }}
                                        @elseif(auth('vendor')->check())
                                        {{ auth('vendor')->user()->name }}
                                        @elseif(auth('fieldagent')->check())
                                        {{ auth('fieldagent')->user()->name }}
                                        @else
                                        Welcome, Guest
                                        @endif
                                    </h6>
                                    {{-- <small class="mb-0 dropdown-user-designation text-secondary">HR Manager</small> --}}
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        @if(auth()->guard('admin')->check())
                        <a class="dropdown-item" href="{{route('admin.logout')}}">
                            <div class="d-flex align-items-center">
                                <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                                <div class="setting-text ms-3"><span>Logout</span></div>
                            </div>
                        </a>
                        @elseif(auth()->guard('vendor')->check())
                        <a class="dropdown-item" href="{{route('vendor.logout')}}">
                            <div class="d-flex align-items-center">
                                <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                                <div class="setting-text ms-3"><span>Logout</span></div>
                            </div>
                        </a>
                        @elseif(auth()->guard('fieldagent')->check())
                        <a class="dropdown-item" href="{{route('fieldagent.logout')}}">
                            <div class="d-flex align-items-center">
                                <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                                <div class="setting-text ms-3"><span>Logout</span></div>
                            </div>
                        </a>
                        @endif
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>