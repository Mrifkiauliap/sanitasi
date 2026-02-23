<x-app-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('pengaturan.manajemen-user.store') }}" enctype="multipart/form-data">
            @csrf

            <x-content-card title="Tambah User" subtitle="Buat user baru" icon="user-plus">
                <x-slot name="action">
                    <a href="{{ route('pengaturan.manajemen-user.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 active:scale-[0.98] disabled:opacity-25 transition-all duration-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <x-forms.text
                            title="Nama Lengkap"
                            name="name"
                            :value="old('name')"
                            required
                            placeholder="Masukkan nama lengkap"
                        />
                    </div>

                    <!-- Username -->
                    <div>
                        <x-forms.text
                            title="Username"
                            name="username"
                            :value="old('username')"
                            required
                            placeholder="Masukkan username"
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-forms.text
                            title="Email"
                            name="email"
                            type="email"
                            :value="old('email')"
                            required
                            placeholder="Masukkan alamat email"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <!-- Password -->
                        <div>
                            <x-forms.password
                                title="Password"
                                name="password"
                                required
                                placeholder="Masukkan password"
                            />
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                             <x-forms.password
                                title="Konfirmasi Password"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi password"
                            />
                        </div>
                    </div>

                    <!-- Foto Profile -->
                    <div>
                        <x-input-label for="image" :value="__('Foto Profil')" />
                        <input id="image" name="image" type="file" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, JPEG, WEBP (Max. 2MB)</p>
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-secondary-button type="reset">
                            Reset
                        </x-secondary-button>
                        <x-primary-button>
                            Simpan
                        </x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
