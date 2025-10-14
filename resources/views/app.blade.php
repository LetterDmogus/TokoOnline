<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('storage/favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body data-bs-theme="dark" class="bg-dark text-light">
    {{-- Include navbar --}}
    @if (!Request::is('login') || !Request::is('register'))
        @include('partial.navbar')
    @endif

    <div class="d-flex">
        {{-- Include sidebar --}}
        @if (!Request::is('login') || !Request::is('register'))
            @include('partial.sidebar')
        @endif

        {{-- Main content area --}}
        <main class="flex-grow-1 p-3">
            @yield('content')
        </main>
    </div>

    {{-- Include footer --}}
    @include('partial.footer')
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const toggleBtn = document.getElementById("toggleSidebar");

        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
            content.classList.toggle("with-sidebar");
        });
    });
</script>

</html>