<x-app-layout>
    <div class="space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>

        <div class="grid grid-cols-1 gap-6">
            <!-- Update Profile Information -->
            <x-content-card title="Informasi Profil" subtitle="Update informasi profil dan alamat email akun Anda." icon="user">
                @include('profile.partials.update-profile-information-form')
            </x-content-card>

            <!-- Update Password -->
            <x-content-card title="Update Password" subtitle="Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman." icon="lock">
                @include('profile.partials.update-password-form')
            </x-content-card>

            <!-- Delete User -->
            <x-content-card title="Hapus Akun" subtitle="Setelah akun Anda dihapus, semua data dan sumber daya akan dihapus secara permanen." icon="trash-2">
                @include('profile.partials.delete-user-form')
            </x-content-card>
        </div>
    </div>
</x-app-layout>
