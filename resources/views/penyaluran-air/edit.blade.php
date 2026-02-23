<x-app-layout>
    <div class="space-y-6">
        <form method="POST" action="{{ route('penyaluran-air.update', $penyaluranAir->id) }}">
            @csrf
            @method('PUT')

            <x-content-card title="Edit Penyaluran Air" subtitle="Ubah data distribusi air bersih" icon="droplets">
                <x-slot name="action">
                    <a href="{{ route('penyaluran-air.index') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 active:scale-[0.98] disabled:opacity-25 transition-all duration-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    <!-- Wilayah -->
                    <div>
                        <x-forms.select
                            title="Wilayah Tujuan"
                            name="wilayah_id"
                            :options="collect($wilayahs)->pluck('nama', 'id')->toArray()"
                            :selected="old('wilayah_id', $penyaluranAir->wilayah_id)"
                            placeholder="-- Pilih Wilayah --"
                        />
                    </div>

                    <!-- Sumber Air & Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.text
                                title="Sumber Air"
                                name="sumber_air"
                                :value="old('sumber_air', $penyaluranAir->sumber_air)"
                                required
                                placeholder="Contoh: PDAM, Tandon, Sumur Bor"
                            />
                        </div>
                        <div>
                            <x-forms.date
                                title="Tanggal Distribusi"
                                name="tanggal_distribusi"
                                :value="old('tanggal_distribusi', $penyaluranAir->tanggal_distribusi?->format('Y-m-d'))"
                                required
                            />
                        </div>
                    </div>

                    <!-- Volume & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.text
                                title="Volume (Liter)"
                                name="volume_liter"
                                type="number"
                                :value="old('volume_liter', $penyaluranAir->volume_liter)"
                                placeholder="Contoh: 5000 (opsional)"
                                min="1"
                            />
                        </div>
                        <div>
                            <x-forms.select
                                title="Status Distribusi"
                                name="status"
                                :options="['terdistribusi' => 'Terdistribusi', 'belum terdistribusi' => 'Belum Terdistribusi']"
                                :selected="old('status', $penyaluranAir->status)"
                                required
                            />
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <x-forms.textarea
                            title="Keterangan"
                            name="keterangan"
                            :value="old('keterangan', $penyaluranAir->keterangan)"
                            placeholder="Catatan tambahan (opsional)"
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
