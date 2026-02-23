<x-app-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('manajemen-data.wilayah.store') }}">
            @csrf

            <x-content-card title="Tambah Wilayah" subtitle="Daftarkan wilayah baru" icon="map-pin">
                <x-slot name="action">
                    <a href="{{ route('manajemen-data.wilayah.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 active:scale-[0.98] disabled:opacity-25 transition-all duration-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <x-forms.text
                            title="Nama Wilayah"
                            name="nama"
                            :value="old('nama')"
                            required
                            placeholder="Contoh: Gampong Blang"
                        />
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <x-forms.text
                            title="Kecamatan"
                            name="kecamatan"
                            :value="old('kecamatan')"
                            required
                            placeholder="Contoh: Langsa Baro"
                        />
                    </div>

                    <!-- Status -->
                    <div>
                        <x-forms.select
                            title="Status Wilayah"
                            name="status"
                            :options="['terdampak' => 'Terdampak', 'tidak terdampak' => 'Tidak terdampak']"
                            :selected="old('status', 'terdampak')"
                            required
                        />
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <x-forms.textarea
                            title="Deskripsi"
                            name="deskripsi"
                            :value="old('deskripsi')"
                            placeholder="Keterangan kondisi umum wilayah (opsional)"
                        />
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-secondary-button type="reset">Reset</x-secondary-button>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
