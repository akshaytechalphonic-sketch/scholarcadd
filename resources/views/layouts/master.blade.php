<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- dir="{{ getLocaleDirection() }}" --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Scholar ACAD') }}</title>
    <link rel="stylesheet" href="{{ asset('amybuild/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('amybuild/css/toastr.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    @production
        @php $path = public_path('build\assets'); @endphp

        @if (file_exists($path))
            @foreach (scandir($path) as $file)
                @if (strpos($file, '.css'))
                    <link rel="stylesheet" href="{{ asset('build/assets/' . $file) }}">
                @endif
                @if (strpos($file, '.js'))
                    @push('scripts')
                        <script src="{{ asset('build/assets/' . $file) }}"></script>
                    @endpush()
                @endif
            @endforeach
        @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'build')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased  transition-colors duration-200 ">
    <div class=" ">
        @include('layouts.loader')
        @yield('Page_content')
    </div>
    <div id="status-message" data-status="{{ session('success') }}" style="display: none;"></div>
    <div id="error-message" data-error="{{ session('errors') }}" style="display: none;"></div>
</body>

<script src="{{ URL::asset('amybuild/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('amybuild/js/toastr.min.js') }}"></script>
@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var statusMessageElement = document.getElementById('status-message');
        if (statusMessageElement) {
            var statusMessage = statusMessageElement.getAttribute('data-status');
            if (statusMessage) {
                toastr.success(statusMessage);
            }
        }
        var errorMessageElement = document.getElementById('error-message');
        if (errorMessageElement) {
            var errorMessage = errorMessageElement.getAttribute('data-error');
            if (errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        }
    });
</script>
</html>
