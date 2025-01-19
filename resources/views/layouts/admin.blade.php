<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Kantin') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-blue-600">
                    <span class="text-white text-xl font-bold">E-Kantin Admin</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <div class="px-4 space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 h-5"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>

                        <!-- Landing Page Management -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Landing Page</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.landing.content') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.landing.content') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-edit w-5 h-5"></i>
                                    <span class="ml-3">Content & Banner</span>
                                </a>
                                <a href="{{ route('admin.landing.announcements') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.landing.announcements') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-bullhorn w-5 h-5"></i>
                                    <span class="ml-3">Announcements</span>
                                </a>
                            </div>
                        </div>

                        <!-- User Management -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-users w-5 h-5"></i>
                                    <span class="ml-3">All Users</span>
                                </a>
                                <a href="{{ route('admin.users.teachers') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->is('admin/users/teachers') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
                                    <span class="ml-3">Teachers/Staff</span>
                                </a>
                                <a href="{{ route('admin.users.students') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->is('admin/users/students') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-user-graduate w-5 h-5"></i>
                                    <span class="ml-3">Students</span>
                                </a>
                                <a href="{{ route('admin.users.parents') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->is('admin/users/parents') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-user-friends w-5 h-5"></i>
                                    <span class="ml-3">Parents</span>
                                </a>
                            </div>
                        </div>

                        <!-- Canteen Management -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Canteen Management</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.canteen.hours') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.canteen.hours') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-clock w-5 h-5"></i>
                                    <span class="ml-3">Operating Hours</span>
                                </a>
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-tags w-5 h-5"></i>
                                    <span class="ml-3">Menu Categories</span>
                                </a>
                                <a href="{{ route('admin.products.index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-utensils w-5 h-5"></i>
                                    <span class="ml-3">Menu Items</span>
                                </a>
                            </div>
                        </div>

                        <!-- Digital Cards -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Digital Cards</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.cards.generate') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.cards.generate') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-qrcode w-5 h-5"></i>
                                    <span class="ml-3">Generate QR</span>
                                </a>
                                <a href="{{ route('admin.cards.print') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.cards.print') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-print w-5 h-5"></i>
                                    <span class="ml-3">Print Cards</span>
                                </a>
                                <a href="{{ route('admin.cards.batch') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.cards.batch') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-layer-group w-5 h-5"></i>
                                    <span class="ml-3">Batch Generation</span>
                                </a>
                            </div>
                        </div>

                        <!-- Financial Management -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.finance.deposits') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.finance.deposits') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-hand-holding-usd w-5 h-5"></i>
                                    <span class="ml-3">Deposits</span>
                                </a>
                                <a href="{{ route('admin.finance.transactions') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.finance.transactions') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-exchange-alt w-5 h-5"></i>
                                    <span class="ml-3">Transactions</span>
                                </a>
                                <a href="{{ route('admin.finance.reports') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.finance.reports') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-chart-line w-5 h-5"></i>
                                    <span class="ml-3">Financial Reports</span>
                                </a>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="pt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</p>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.settings.general') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.settings.general') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-cog w-5 h-5"></i>
                                    <span class="ml-3">General Settings</span>
                                </a>
                                <a href="{{ route('admin.settings.notifications') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.settings.notifications') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-bell w-5 h-5"></i>
                                    <span class="ml-3">Notifications</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- User Menu -->
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="px-4 py-6 mx-auto">
                    @yield('header')
                </div>
            </header>

            <!-- Page Content -->
            <main class="pb-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 