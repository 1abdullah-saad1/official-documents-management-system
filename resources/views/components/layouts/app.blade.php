<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>{{ __('app.project_name') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}" type="image/x-icon" />

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @font-face {
            font-family: "Cairo";
            src: url({{ asset('assets/fonts/Cairo-SemiBold.ttf') }});
        }

        body {
            font-family: 'Cairo', sans-serif;
        }

        .app-header-shadow {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .app-header-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
            align-items: center;
        }

        .app-logo {
            font-size: 1.125rem;
            font-weight: 700;
        }

        .btn-clear {
            background: transparent;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
        }

        .btn-clear:hover {
            opacity: 0.7;
        }
    </style>

    @stack('styles')

    <!-- Day.js Library -->
    <script src="{{ asset('assets/js/dayjs.min.js') }}"></script>
    <!-- SweetAlert2 Library -->
    <script src="{{ asset('assets/js/sweetalert2@11.min.js') }}"></script>
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        @auth
            @include('components.layouts.sidebar')
            <header class="p-2 mb-3 border-bottom app-header-shadow">
                <div class="container-fluid app-header-grid">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="35" height="35"
                            class="mx-2 rounded-circle">
                        <div class="app-logo">
                            {{ __('منظومة ادارة الوثائق الرسمية') }}
                        </div>
                        <button class="btn btn-clear" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                        <div class="app-logo mx-2">

                            {{ '  ' . auth()->user()->institution ? auth()->user()->institutionname : 'المشرف العام' }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="w-100 me-3">
                        </div>
                        <div class="shrink-0 dropdown">
                            <a href="#"
                                class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if (session('avatarGoogleUser'))
                                    <img src="{{ session('avatarGoogleUser') }}" alt="{{ Auth::user()->name }}"
                                        width="35" height="35" class="rounded-circle me-2">
                                @else
                                    <img src="{{ asset('assets/images/profile-avatar.png') }}"
                                        alt="{{ Auth::user()->name }}" width="35" height="35"
                                        class="rounded-circle me-2">
                                @endif
                            </a>
                            <ul class="dropdown-menu text-small shadow">
                                <li>
                                    <label class="dropdown-item">
                                        {{ Auth::user()->name }}
                                    </label>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                        <i class="fa fa-lock mx-2"></i>
                                        {{ __('تسجيل الخروج') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
        @endauth
        <main class="py-4">
            <div class="container">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
