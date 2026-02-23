<x-app-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('laporan-kondisi.store') }}">
            @csrf

            <x-content-card title="Tambah Laporan Kondisi" subtitle="Catat hasil inspeksi kondisi wilayah" icon="clipboard-list">
                <x-slot name="action">
                    <a href="{{ route('laporan-kondisi.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 active:scale-[0.98] disabled:opacity-25 transition-all duration-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    {{-- Wilayah & Tanggal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.select
                                title="Wilayah"
                                name="wilayah_id"
                                :options="collect($wilayahs)->pluck('nama', 'id')->toArray()"
                                :selected="old('wilayah_id')"
                                placeholder="-- Pilih Wilayah --"
                                required
                            />
                        </div>
                        <div>
                            <x-forms.date
                                title="Tanggal Inspeksi"
                                name="tanggal_inspeksi"
                                :value="old('tanggal_inspeksi', now()->format('Y-m-d'))"
                                required
                            />
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <x-forms.textarea
                            title="Catatan Kondisi"
                            name="catatan"
                            :value="old('catatan')"
                            placeholder="Deskripsikan kondisi wilayah hasil inspeksi (opsional)"
                        />
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-secondary-button type="reset">Reset</x-secondary-button>
                        <x-primary-button>Simpan Laporan</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
