@php
    // Fetch user profile picture seamlessly from Laravel Auth session
    $user = auth()->user();
    $avatarFile = ($user && !empty($user->profile_pic)) ? $user->profile_pic : 'default.png';
    $avatarUrl = asset('uploads/' . $avatarFile);
@endphp

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .custom-navbar {
        background-color: #0f172a !important; /* Premium deep slate background */
        padding: 0.85rem 0;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .custom-navbar .navbar-brand {
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #f8fafc !important;
    }

    .nav-group-links .nav-link-custom {
        color: #94a3b8 !important;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.5rem 0.85rem;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .nav-group-links .nav-link-custom:hover {
        color: #f1f5f9 !important;
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Target the link matching the current active script path */
    .nav-group-links .nav-link-custom.active {
        color: #3b82f6 !important; /* Accent blue color marker */
        background-color: rgba(59, 130, 246, 0.1);
        font-weight: 600;
    }

    .user-profile-badge {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 0.35rem 0.75rem 0.35rem 0.4rem;
        border-radius: 50px;
        transition: border-color 0.2s ease;
    }

    .user-profile-badge:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .nav-avatar-img {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
    }

    .btn-logout-custom {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.45rem 0.85rem;
        border-radius: 6px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #f87171 !important;
        transition: all 0.2s ease;
        text-decoration: none;
        background: transparent;
        cursor: pointer;
    }

    .btn-logout-custom:hover {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border-color: #ef4444 !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }
</style>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <span class="text-primary me-1">⚡</span> Task Manager
        </a>
        
        <div class="d-flex align-items-center ms-auto">
            
            <div class="nav-group-links d-flex align-items-center me-3">
                <a href="{{ route('dashboard') }}" class="nav-link-custom me-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('tasks.index') }}" class="nav-link-custom me-1 {{ request()->routeIs('tasks.*') ? 'active' : '' }}">Manage Tasks</a>
                <a href="/myprofile" class="nav-link-custom {{ request()->is('myprofile') ? 'active' : '' }}">My Profile</a>
            </div>

            <div style="width: 1px; height: 20px; background-color: rgba(255,255,255,0.15);" class="me-3"></div>

            <div class="d-flex align-items-center">
                <div class="user-profile-badge d-flex align-items-center me-3">
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle nav-avatar-img me-2">
                    <span class="small text-white-50">Hi, <strong class="text-white fw-medium">{{ $user->username ?? 'User' }}</strong></span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn-logout-custom">Logout</button>
                </form>
            </div>

        </div>
    </div>
</nav>