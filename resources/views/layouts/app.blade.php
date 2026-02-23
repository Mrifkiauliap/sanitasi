<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIBANHUM') }}</title>

    <!-- CDN Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-NhfZj0MXBZYr1Py6kS3OXLtjQwXDGNANTZj3QN8cOWdDdknSv3zJX2DuPhE0AIh2k1/y5+ekUNhhkOdQVnDQ==" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div x-data="{
        sidebarOpen: false,
        sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed')) || false,
        isMobile() {
            return window.innerWidth < 1024; // lg breakpoint
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        },
        getGreeting() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 11) return 'Selamat Pagi';
            if (hour >= 11 && hour < 15) return 'Selamat Siang';
            if (hour >= 15 && hour < 18) return 'Selamat Sore';
            return 'Selamat Malam';
        },
        getGreetingIcon() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 11) return '☀️';
            if (hour >= 11 && hour < 15) return '🌤️';
            if (hour >= 15 && hour < 18) return '🌅';
            return '🌙';
        },
        isEffectiveCollapsed() {
            // Mobile: SELALU full width, tidak pernah collapsed
            if (this.isMobile()) return false;
            // Desktop: collapsed hanya jika diset DAN tidak sedang di-hover
            return this.sidebarCollapsed;
        }
    }"
    x-init="
        // Update isMobile reactively on resize
        window.addEventListener('resize', () => { $data.sidebarOpen = false; });
    "
    class="flex h-screen bg-background">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header -->
            <header class="bg-white border-b border-gray-100 sticky top-0 z-10 shadow-[0_1px_3px_0_rgba(0,0,0,0.06)]">
                <div class="flex justify-between items-center px-6 h-16">

                    <!-- Left: Mobile Toggle + Page Title -->
                    <div class="flex items-center gap-4">
                        <button x-on:click.stop="sidebarOpen = !sidebarOpen"
                                class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors lg:hidden">
                            <x-lucide-menu class="w-5 h-5" />
                        </button>

                        <!-- Page title -->
                        <div class="hidden sm:flex flex-col">
                            <h1 class="text-base font-bold text-gray-900 leading-none">
                                {{ $pageTitle ?? config('app.name') }}
                            </h1>
                            <p class="text-xs text-gray-400 mt-0.5" x-text="getGreeting() + ', ' + '{{ ucfirst(Auth::user()->username ?? Auth::user()->name) }}'"></p>
                        </div>
                    </div>

                    <!-- Right: Greeting icon + user menu -->
                    <div class="flex items-center gap-3">
                        <!-- Greeting emoji + time -->
                        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100">
                            <span x-text="getGreetingIcon()" class="text-sm"></span>
                            <span class="text-xs font-medium text-gray-600" x-text="getGreeting()"></span>
                        </div>

                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                            <button x-on:click="open = !open"
                                    class="flex items-center gap-2.5 px-2 py-1.5 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-200 transition-all">
                                @if(Auth::user()->photo_path)
                                    <img src="{{ Storage::url(Auth::user()->photo_path) }}" class="w-8 h-8 rounded-full object-cover shadow-sm border border-gray-100" />
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="hidden sm:block text-left">
                                    <p class="text-xs font-semibold text-gray-800 leading-none">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Administrator</p>
                                </div>
                                <x-lucide-chevron-down class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50"
                                 style="display:none">
                                <div class="px-4 py-3 border-b border-gray-50 flex items-center gap-3">
                                    @if(Auth::user()->photo_path)
                                        <img src="{{ Storage::url(Auth::user()->photo_path) }}" class="w-10 h-10 rounded-full object-cover border border-gray-100" />
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white text-base font-bold shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->username }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-sky-700 transition-colors">
                                    <x-lucide-user class="w-4 h-4 text-gray-400" />
                                    Profil Saya
                                </a>
                                <div class="border-t border-gray-50 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <x-lucide-log-out class="w-4 h-4" />
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-background p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
    <x-toast-notification
        default-position="top-right"
        :max-toasts="5"
        theme="light" />
    <x-modal-confirm theme="light" />

    @if (session('notification'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('open-toast', {
                    detail: {
                        type: '{{ session('notification.type') }}',
                        title: '{{ session('notification.title') }}',
                        message: '{{ session('notification.message') }}',
                        autoClose: {{ data_get(session('notification'), 'autoClose', true) ? 'true' : 'false' }},
                        dismissible: {{ data_get(session('notification'), 'dismissible', true) ? 'true' : 'false' }},
                        duration: {{ data_get(session('notification'), 'duration', 5000) }}
                    }
                }));
            });
        </script>
    @endif

    @if (session('confirm'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                    detail: {
                        type: '{{ session('confirm.type') }}',
                        title: '{{ session('confirm.title') }}',
                        message: '{{ session('confirm.message') }}',
                        action: '{{ session('confirm.action') }}',
                        method: '{{ session('confirm.method') }}',
                        size: data_get(session('confirm'), 'size', 'md')
                    }
                }));
            });
        </script>
    @endif
    @stack('scripts')
</body>
</html>
