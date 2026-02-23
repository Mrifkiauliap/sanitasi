<x-app-layout>
    <div class="space-y-6">
        <x-content-card title="Penyaluran Air Bersih" subtitle="Distribusi bantuan air ke wilayah terdampak" icon="droplets">
            <x-slot name="action">
                <a href="{{ route('penyaluran-air.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Data
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('penyaluran-air.index') }}"
                :initial-filters="['wilayah_id' => null, 'status' => null, 'start_date' => null, 'end_date' => null]">

                {{-- Filter --}}
                <x-slot name="filters">
                    {{-- Wilayah --}}
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

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select x-model="filters.status"
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Semua Status —</option>
                            <option value="terdistribusi">Terdistribusi</option>
                            <option value="belum terdistribusi">Belum Terdistribusi</option>
                        </select>
                    </div>

                    {{-- Dari Tanggal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" x-model="filters.start_date"
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    {{-- Sampai Tanggal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" x-model="filters.end_date"
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('tanggal_distribusi')">
                        <div class="flex items-center gap-1">
                            Tanggal
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Wilayah
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('sumber_air')">
                        <div class="flex items-center gap-1">
                            Sumber Air
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('volume_liter')">
                        <div class="flex items-center gap-1">
                            Volume (L)
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
                        Keterangan
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            x-text="item.tanggal_distribusi ? new Date(item.tanggal_distribusi).toLocaleDateString('id-ID') : '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.wilayah ? item.wilayah.nama : '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium" x-text="item.sumber_air"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span x-text="item.volume_liter ? item.volume_liter.toLocaleString('id-ID') + ' L' : '-'"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="item.status === 'terdistribusi'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700'"
                                class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                x-text="item.status">
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" x-text="item.keterangan ?? '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-2">
                                {{-- Tombol Konfirmasi (muncul jika belum terdistribusi) --}}
                                <button type="button"
                                        x-show="item.status === 'belum terdistribusi'"
                                        @click="$dispatch('open-confirm-modal', {
                                            title: 'Konfirmasi Distribusi',
                                            message: 'Tandai distribusi dari <strong>' + item.sumber_air + '</strong> sebagai sudah terdistribusi?',
                                            action: `/penyaluran-air/${item.id}/status?status=terdistribusi`,
                                            method: 'PATCH',
                                            type: 'success',
                                            confirmText: 'Ya, Konfirmasi'
                                        })"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                    Konfirmasi
                                </button>

                                {{-- Tombol Batalkan (muncul jika terdistribusi) --}}
                                <button type="button"
                                        x-show="item.status === 'terdistribusi'"
                                        @click="$dispatch('open-confirm-modal', {
                                            title: 'Batalkan Distribusi',
                                            message: 'Apakah Anda yakin ingin mengembalikan status distribusi <strong>' + item.sumber_air + '</strong> ke belum terdistribusi?',
                                            action: `/penyaluran-air/${item.id}/status?status=belum terdistribusi`,
                                            method: 'PATCH',
                                            type: 'warning',
                                            confirmText: 'Ya, Batalkan'
                                        })"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-50 text-gray-600 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <x-lucide-undo-2 class="w-3.5 h-3.5" />
                                    Batalkan
                                </button>

                                <a :href="`/penyaluran-air/${item.id}/edit`"
                                   class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>
                                <button @click="$dispatch('open-confirm-modal', {
                                    type: 'danger',
                                    title: 'Hapus Data Penyaluran',
                                    message: 'Apakah Anda yakin ingin menghapus data distribusi <strong>' + item.sumber_air + '</strong>?',
                                    action: `/penyaluran-air/${item.id}`,
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
