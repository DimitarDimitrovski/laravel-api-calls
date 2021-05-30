<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head')
</head>
<body class="antialiased bg-gray-100">
    <div class="header-menu">
        @include('layouts.header')
    </div>
    </div>
    <div class="container">
        <div class="row body-content">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
