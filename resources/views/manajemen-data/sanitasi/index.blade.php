<x-app-layout>
    <div class="space-y-6">
        <x-content-card title="Data Sanitasi" subtitle="Kondisi fasilitas sanitasi per wilayah" icon="filter">
            <x-slot name="action">
                <a href="{{ route('manajemen-data.sanitasi.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Sanitasi
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('manajemen-data.sanitasi.index') }}"
                :initial-filters="['wilayah_id' => null, 'status' => null]">

                {{-- Filter --}}
                <x-slot name="filters">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Wilayah</label>
                        <select x-model="filters.wilayah_id"
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Semua Wilayah —</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}">{{ $w->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select x-model="filters.status"
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Semua Status —</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                            <option value="tidak ada">Tidak Ada</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="clearFilters()" type="button"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <x-lucide-x class="w-3.5 h-3.5" />
                            Reset
                        </button>
                    </div>
                </x-slot>

                <x-slot name="thead">
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('jenis')">
                        <div class="flex items-center gap-1">
                            Jenis
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('status')">
                        <div class="flex items-center gap-1">
                            Status
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Wilayah
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Lokasi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keterangan
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="item.jenis"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="{
                                    'bg-green-100 text-green-700': item.status === 'baik',
                                    'bg-red-100 text-red-700': item.status === 'rusak',
                                    'bg-gray-100 text-gray-500': item.status === 'tidak ada',
                                }"
                                class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                x-text="item.status">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.wilayah ? item.wilayah.nama : '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.lokasi"></td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" x-text="item.keterangan ?? '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a :href="`/manajemen-data/sanitasi/${item.id}/edit`"
                                   class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>
                                <button @click="$dispatch('open-confirm-modal', {
                                    type: 'danger',
                                    title: 'Hapus Data Sanitasi',
                                    message: 'Apakah Anda yakin ingin menghapus data <strong>' + item.jenis + '</strong>?',
                                    action: `/manajemen-data/sanitasi/${item.id}`,
                                    method: 'DELETE',
                                    size: 'md',
                                    confirmText: 'Ya, Hapus'
                                })" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </x-slot>
            </x-data-table>

        </x-content-card>
    </div>
</x-app-layout>
