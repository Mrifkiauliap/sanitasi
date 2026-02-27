<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SANITASI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col lg:flex-row overflow-hidden">

        <!-- Left Side: Dynamic Branding (Desktop Only) -->
        <div class="hidden lg:flex lg:w-3/5 relative bg-gradient-to-br from-sky-600 via-blue-700 to-indigo-800 p-16 flex-col justify-between overflow-hidden">
            {{-- Decorative Abstract Elements --}}
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -left-24 w-64 h-64 bg-sky-400/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 right-1/4 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl"></div>

                {{-- Dynamic SVG Wave/Pattern (Optional but cool) --}}
                <svg class="absolute bottom-0 left-0 w-full h-64 text-white/5" viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path fill="currentColor" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl shadow-xl shadow-black/5 ring-1 ring-white/30">
                        <x-lucide-droplets class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <span class="text-2xl font-black tracking-tighter text-white uppercase">{{ config('app.name', 'SANITASI') }}</span>
                        <p class="text-[10px] text-sky-200 font-bold tracking-[0.2em] uppercase mt-0.5">Sanitasi & Air Pasca Bencana</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 max-w-xl">
                <h1 class="text-5xl font-black text-white leading-[1.1] mb-6 tracking-tight">
                    Pengelolaan Krisis <span class="text-sky-300">Respons Cepat</span> Air Bersih.
                </h1>
                <p class="text-lg text-sky-100/80 leading-relaxed font-medium">
                    Sistem monitoring terpusat untuk memastikan setiap wilayah terdampak bencana mendapatkan akses sanitasi layak dan distribusi air bersih yang tepat sasaran.
                </p>
            </div>

            <div class="relative z-10 flex items-center justify-between text-sky-200/50 text-xs font-semibold tracking-wider uppercase">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'SANITASI') }}</span>
                <div class="flex gap-4">
                    <span>{{ config('app.version', 'V1.0.0') }}</span>
                    <span>Support</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Interaction Area -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12 relative bg-white">
            {{-- Mobile Decor --}}
            <div class="lg:hidden absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 to-indigo-600"></div>

            <div class="w-full max-w-[400px] animate-in fade-in slide-in-from-bottom-4 duration-700">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
