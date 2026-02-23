<x-app-layout>
    <div class="space-y-6">
        <x-content-card title="Laporan Kondisi" subtitle="Riwayat inspeksi kondisi wilayah oleh petugas" icon="clipboard-list">
            <x-slot name="action">
                <a href="{{ route('laporan-kondisi.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Laporan
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('laporan-kondisi.index') }}"
                seamless
                :initial-filters="[
                    'wilayah_id' => null,
                    'start_date' => null,
                    'end_date' => null,
                ]">

                {{--  Slot Filter  --}}
                <x-slot name="filters">
                    {{-- Wilayah --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Wilayah</label>
                        <select x-model="filters.wilayah_id"
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Semua —</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}">{{ $w->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" x-model="filters.start_date"
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" x-model="filters.end_date"
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    {{-- Aksi Filter --}}
                    <div class="flex flex-col gap-2 justify-end">
                        <button @click="
                            filters.wilayah_id = '';
                            filters.start_date = '';
                            filters.end_date = '';
                        " class="text-sm text-gray-500 hover:text-gray-800 underline text-center">
                            Reset Filter
                        </button>

                        {{-- Export: URL dibangun dari filters yang aktif --}}
                        <a :href="(() => {
                                const p = new URLSearchParams();
                                if (filters.wilayah_id) p.set('wilayah_id', filters.wilayah_id);
                                if (filters.start_date) p.set('start_date', filters.start_date);
                                if (filters.end_date)   p.set('end_date', filters.end_date);
                                return '{{ route('laporan-kondisi.export') }}?' + p.toString();
                            })()"
                           class="inline-flex items-center gap-2 justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-lucide-file-spreadsheet class="w-4 h-4" />
                            Export Excel
                        </a>
                    </div>
                </x-slot>

                {{--  Kolom Header  --}}
                <x-slot name="thead">
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group"
                        @click="sortBy('tanggal_inspeksi')">
                        <div class="flex items-center gap-1">
                            Tanggal Inspeksi
                            <x-lucide-arrow-up-down class="w-3 h-3 text-gray-400 group-hover:text-gray-600" />
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </x-slot>

                {{--  Baris Data  --}}
                <x-slot name="tbody">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            x-text="item.tanggal_inspeksi
                                ? new Date(item.tanggal_inspeksi).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
                                : '-'">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            <div x-text="item.wilayah?.nama ?? '-'"></div>
                            <div class="text-xs text-gray-500" x-text="item.wilayah?.kecamatan ?? ''"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.petugas?.name ?? '-'"></td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                            <p class="line-clamp-2" x-text="item.catatan ?? '-'"></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a :href="`/laporan-kondisi/${item.id}/edit`"
                                   class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>
                                <button @click="$dispatch('open-confirm-modal', {
                                    type: 'danger',
                                    title: 'Hapus Laporan?',
                                    message: 'Laporan inspeksi pada <strong>' + (item.wilayah?.nama ?? '-') + '</strong> akan dihapus permanen.',
                                    action: `/laporan-kondisi/${item.id}`,
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
