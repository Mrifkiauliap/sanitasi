<x-app-layout>
    <div class="space-y-6">
        <x-content-card title="Manajemen User" subtitle="Kelola data pengguna aplikasi" icon="users">
            <x-slot name="action">
                <a href="{{ route('pengaturan.manajemen-user.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah User
                </a>
            </x-slot>

            <x-data-table url="{{ route('pengaturan.manajemen-user.index') }}">
                <x-slot name="thead">
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('name')">
                        <div class="flex items-center gap-1">
                            Nama
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('username')">
                         <div class="flex items-center gap-1">
                            Username
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('email')">
                         <div class="flex items-center gap-1">
                            Email
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('status')">
                        <div class="flex items-center gap-1">
                            Status
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <template x-if="item.photo_path">
                                        <img class="h-10 w-10 rounded-full object-cover" :src="'/storage/' + item.photo_path" :alt="item.name">
                                    </template>
                                    <template x-if="!item.photo_path">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold" x-text="item.name.charAt(0)"></div>
                                    </template>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900" x-text="item.name"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.username"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.email"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.status"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2" x-data="{ currentUserId: {{ auth()->id() }} }">
                                <template x-if="item.id !== currentUserId">
                                    <div class="flex gap-2">
                                        <a :href="`/pengaturan/manajemen-user/${item.id}/edit`" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded">
                                            <x-lucide-edit class="w-4 h-4" />
                                        </a>
                                        <template x-if="item.status === 'active'">
                                            <button @click="$dispatch('open-confirm-modal', {
                                                type: 'danger',
                                                title: 'Nonaktifkan User',
                                                message: 'Apakah Anda yakin ingin menonaktifkan user ' + item.name + '?',
                                                action: `/pengaturan/manajemen-user/${item.id}/status`,
                                                method: 'PUT',
                                                size: 'md',
                                                confirmText: 'Ya, Nonaktifkan'
                                            })" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded">
                                                <x-lucide-ban class="w-4 h-4" />
                                            </button>
                                        </template>
                                        <template x-if="item.status === 'inactive'">
                                            <button @click="$dispatch('open-confirm-modal', {
                                                type: 'success',
                                                title: 'Aktifkan User',
                                                message: 'Apakah Anda yakin ingin mengaktifkan user ' + item.name + '?',
                                                action: `/pengaturan/manajemen-user/${item.id}/status`,
                                                method: 'PUT',
                                                size: 'md',
                                                confirmText: 'Ya, Aktifkan'
                                            })" class="text-green-600 hover:text-green-900 p-1 hover:bg-green-50 rounded">
                                                <x-lucide-check-circle class="w-4 h-4" />
                                            </button>
                                        </template>
                                        <button @click="$dispatch('open-confirm-modal', {
                                            type: 'danger',
                                            title: 'Hapus User',
                                            message: 'Apakah Anda yakin ingin menghapus user ' + item.name + '?',
                                            action: `/pengaturan/manajemen-user/${item.id}`,
                                            method: 'DELETE',
                                            size: 'md'
                                        })" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </template>
                                <template x-if="item.id === currentUserId">
                                    <span class="text-xs text-gray-400 italic py-1">Akun Sendiri</span>
                                </template>
                            </div>
                        </td>
                    </tr>
                </x-slot>
            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
