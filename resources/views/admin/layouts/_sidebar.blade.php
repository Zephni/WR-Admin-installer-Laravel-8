<div class="sidebar-brand">
    <a href="/">{{ config('app.name') }}</a>
    <div id="close-sidebar">
        <i class="fas fa-times"></i>
    </div>
</div>
<div class="sidebar-header">
    <div class="user-pic square-image-container ">
        <img class="image-preview cover-fit-center img-responsive img-rounded"
            src="{{ Auth::user()->getProfileImage(true) }}"
            alt="User picture">
    </div>
    <div class="user-info">
        <span class="user-name">
            {{ Auth::user()->name }}
        </span>
        <span class="user-role">Administrator</span>
        <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Online</span>
            | <a href="{{ route('logout') }}"
                class="text-primary"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
        </span>
    </div>
</div>
<!-- sidebar-header  -->
<div class="sidebar-search">
    <div>
        <div class="input-group">
            <input type="text" class="form-control search-menu" placeholder="Search...">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar-search  -->

<div class="sidebar-menu">
    <ul>

        @foreach (\App\Http\Controllers\AdminController::$navigation as $key => $value)

            @if (is_string($value))
                
                <li class="header-menu">
                    <span>{{ $value }}</span>
                </li>

            @else

                @if(substr($key, 0, strlen('protected')) == 'protected')
                    <?php
                        $splitkey = explode(':',$key);

                        if($splitkey[1] == 'zephni' && auth()->user()->id != 1)
                            continue;
                        elseif($splitkey[1] != 'zephni')
                            continue;

                        $key = $splitkey[2];
                    ?>
                @endif
                
                @if (array_key_exists('submenu', $value))
                    <li class="sidebar-dropdown">
                @else
                    <li>
                @endif
                    <a href="{{ $value['url'] }}">
                        <i class="fa {{ $value['icon'] ?? 'fa-gem' }}"></i>
                        <span>{{ $key }}</span>
                    </a>
                    <div class="drop-button"></div>
                @if (array_key_exists('submenu', $value))
                    <div class="sidebar-submenu">
                        <ul>
                            @foreach ($value['submenu'] as $key => $value)
                                <li>
                                    <a href="{{ $value['url'] }}">
                                        <span class="pr-2 fa {{ $value['icon'] ?? 'fa-gem' }}"></span> {{ $key }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                </li>

            @endif

            {{-- Bellow example is with drop down, plus badge etc --}}
            {{-- <li class="sidebar-dropdown">
                <a href="#">
                    <i class="fa {{ $value['icon'] ?? 'fa-gem' }}"></i>
                    <span>{{ $key }}</span>
                    <span class="badge badge-pill badge-warning">New</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="#">General</a></li>
                        <li><a href="#">Panels</a></li>
                    </ul>
                </div>
            </li> --}}

        @endforeach

        <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>{{ __('Logout') }}</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</div>
<!-- sidebar-menu  -->