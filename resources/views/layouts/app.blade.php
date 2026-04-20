<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Parking System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <meta name="description" content="Professional Parking Management Dashboard">
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50 min-h-screen antialiased font-inter">
    
    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-gray-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="text-2xl font-black bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent flex items-center gap-3 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-parking text-3xl"></i>
                    Parking Sistem 
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-sm rounded-2xl border border-gray-200 shadow-sm">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('spots.index') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i> Spots
                        </a>
                        <a href="{{ route('reservations.index') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-calendar-check"></i> Reservations
                        </a>
                        <a href="{{ route('payments.index') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-credit-card"></i> Payments
                        </a>
                        <a href="{{ route('laporan.index') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-file-lines"></i> Laporan
                        </a>
                        <a href="{{ route('users.index') }}" class="px-6 py-3 text-lg font-semibold text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </div>
                </nav>

                <!-- Auth Section -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3 px-4 py-2 bg-white/50 backdrop-blur-sm rounded-xl border border-gray-200 shadow-sm">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="ml-2 p-2 hover:bg-red-100 hover:text-red-600 rounded-xl transition-colors text-gray-600 hover:rotate-180">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <a href="{{ route('login') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 rounded-xl hover:bg-gray-200 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white/50 backdrop-blur-xl border-t border-gray-200/50 mt-24">
        <div class="max-w-7xl mx-auto px-6 py-12 text-center">
            <p class="text-lg font-semibold text-gray-700 mb-2">Professional Parking Management Dashboard</p>
            <p class="text-sm text-gray-500">Built with Laravel • © 2024 All Rights Reserved</p>
        </div>
    </footer>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</body>
</html>

