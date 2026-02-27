<x-app-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('manajemen-data.sanitasi.update', $sanitasi->id) }}">
            @csrf
            @method('PUT')

            <x-content-card title="Edit Produk Sanitasi" subtitle="Ubah data stok logistik produk" icon="package">
                <x-slot name="action">
                    <a href="{{ route('manajemen-data.sanitasi.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 active:scale-[0.98] disabled:opacity-25 transition-all duration-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    <!-- Wilayah -->
                    <div>
                        <x-forms.select
                            title="Wilayah / Gudang"
                            name="wilayah_id"
                            :options="$wilayahs->pluck('nama', 'id')->toArray()"
                            :selected="old('wilayah_id', $sanitasi->wilayah_id)"
                            placeholder="-- Pilih Wilayah Penempatan --"
                        />
                    </div>

                    <!-- Nama & Jumlah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.text
                                title="Nama Produk"
                                name="nama"
                                :value="old('nama', $sanitasi->nama)"
                                required
                                placeholder="Contoh: Sabun Mandi, Cairan Disinfektan"
                            />
                        </div>
                        <div>
                            <x-forms.text
                                title="Jumlah Stok (Qty)"
                                name="jumlah"
                                type="number"
                                :value="old('jumlah', $sanitasi->jumlah)"
                                required
                                placeholder="Masukkan kuantitas produk"
                            />
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <x-forms.text
                            title="Titik Distribusi / Lokasi Spesifik"
                            name="lokasi"
                            :value="old('lokasi', $sanitasi->lokasi)"
                            required
                            placeholder="Contoh: Gudang Desa, Kantor RT 02"
                        />
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <x-forms.textarea
                            title="Catatan / Keterangan"
                            name="keterangan"
                            :value="old('keterangan', $sanitasi->keterangan)"
                            placeholder="Informasi tambahan terkait produk (opsional)"
                        />
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-3">
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
