@php($appName = 'Kunjachan Missionary Bhavan')
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $appName) | FOLLOW JESUS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}" aria-label="Home">
            <img src="{{ asset('assets/kunjachanMissionaryLogo.png') }}" alt="{{ $appName }} Logo" class="logo-img">
            <span class="brand-title d-none d-sm-inline">Kunjachan Missionary<br>Bhavan</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li class="nav-item"><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li class="nav-item"><a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
                <li class="nav-item"><a href="{{ route('mission') }}" class="nav-link {{ request()->routeIs('mission') ? 'active' : '' }}">Mission</a></li>
                <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                @auth
                    <li class="nav-item ms-lg-2"><a href="{{ route('dashboard') }}" class="btn btn-kb rounded-pill px-3">Dashboard</a></li>
                @else
                    <li class="nav-item ms-lg-2"><a href="{{ route('login') }}" class="btn btn-kb rounded-pill px-3">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="py-4 text-center small">
    <div class="container">
        <div class="small-note mb-1">&copy; {{ date('Y') }} Kunjachan Missionary Bhavan</div>
        <div class="text-muted small">All rights reserved.</div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
