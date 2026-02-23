<x-guest-layout>
    <div class="space-y-6">
        {{-- Header Form --}}
        <div class="text-center lg:text-left">
            <div class="inline-flex items-center justify-center p-3 bg-indigo-50 rounded-2xl mb-4 lg:hidden">
                <x-lucide-droplets class="w-8 h-8 text-indigo-600" />
            </div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Selamat Datang Kembali!</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda untuk melanjutkan monitoring.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Username -->
            <div>
                <x-forms.text id="username"
                            title="Username"
                            name="username"
                            :value="old('username')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username Anda" />
            </div>

            <!-- Password -->
            <div>
                <x-forms.password id="password"
                                title="Password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between">
                <!-- Remember Me -->
                <x-forms.checkbox id="remember_me" title="Ingat perangkat ini" name="remember" />

                {{-- Placeholder link jika nanti ada fitur lupa password, saat ini disembunyikan sesuai rule --}}
            </div>

            <div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all duration-200 hover:-translate-y-0.5 active:scale-[0.98] focus:ring-4 focus:ring-indigo-100">
                    <span>Masuk ke Dashboard</span>
                    <x-lucide-arrow-right class="w-4 h-4" />
                </button>
            </div>
        </form>

        {{-- Footer Note --}}
        <div class="pt-6 border-t border-gray-100">
            <p class="text-center text-xs text-gray-400">
                Punya masalah saat masuk? <a href="#" class="text-indigo-600 font-semibold hover:underline">Hubungi Admin IT</a>
            </p>
        </div>
    </div>
</x-guest-layout>
