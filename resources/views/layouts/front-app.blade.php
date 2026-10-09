<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<title>
    @yield('page_title')
</title>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Amysoftech-Mart') }}</title>
     <link href="{{ asset('assets/css/style.css?v=3.0.0')}}" rel="stylesheet">
     @php $system = App\Helpers\CommonHelper::system_control(['brand_name','light_logo','small_logo','rtl','def_language']); @endphp
</head>

<body>
    @include('layouts.front-layout.main-loader')
    @include('layouts.front-layout.sub-topbar')
    @include('layouts.front-navbar')
    @yield('main-content')
    {{-- <div>
        <div class="flex h-screen">
            @include('layouts.loader')
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.front-navbar')
                <div class="flex-1 p-3 dark:bg-slate-700  dark:text-white text-black overflow-auto">
                    <div class="responsive-container">
                        @yield('main-content')
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div> --}}
@include('layouts.front-layout.footer')
    <div id="status-message" data-status="{{ session('success') }}" style="display: none;"></div>
    <div id="error-message" data-error="{{ session('error') }}" style="display: none;"></div>
</body>
@include('layouts.front-layout.extrajs')
@yield('scripts')

</html>
