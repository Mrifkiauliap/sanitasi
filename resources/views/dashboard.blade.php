<x-app-layout>
    <div class="space-y-6">

        {{--  Welcome Banner  --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-sky-600 via-blue-600 to-indigo-700 p-7 text-white shadow-xl">
            {{-- Decorative shapes --}}
            <div class="absolute -top-8 -right-8 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-0 right-24 w-32 h-32 bg-teal-400/20 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute top-0 right-0 h-full w-1/3 bg-white/5 skew-x-12 transform translate-x-8 pointer-events-none"></div>

            <div class="relative flex flex-col gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold bg-white/20 text-white/90 px-2.5 py-0.5 rounded-full tracking-wide uppercase">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight drop-shadow">
                        Selamat Datang, {{ Auth::user()->name }}! 👋
                    </h2>
                    <p class="text-sky-100 mt-1.5 text-sm max-w-md">
                        Pantau kondisi sanitasi dan distribusi air bersih wilayah terdampak.
                    </p>
                </div>

                {{-- Quick mini-stats strip — hanya tampil di mobile (sm ke bawah), di desktop digantikan stat cards --}}
                <div class="flex items-center gap-3 flex-wrap sm:hidden">
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ $totalWilayah }}</p>
                        <p class="text-xs text-sky-200 mt-0.5 leading-none">Wilayah</p>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ $totalPending }}</p>
                        <p class="text-xs text-sky-200 mt-0.5 leading-none">Pending</p>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ number_format($distribusiBulanIni / 1000, 0) }}</p>
                        <p class="text-xs text-sky-200 mt-0.5 leading-none">m³ Bulan Ini</p>
                    </div>
                </div>
            </div>
        </div>

        {{--  Stat Cards — hanya tampil di sm ke atas  --}}
        <div class="hidden sm:grid grid-cols-2 md:grid-cols-4 gap-4">

            {{-- Wilayah Terdampak --}}
            <a href="{{ route('manajemen-data.wilayah.index') }}"
               class="group bg-white rounded-xl border border-gray-100 shadow-stat hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200 p-5 overflow-hidden relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-red-400 to-rose-600 rounded-l-xl"></div>
                <div class="flex items-start justify-between pl-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest">Wilayah Terdampak</p>
                        <p class="text-3xl font-black text-gray-900 mt-1.5 leading-none">{{ $wilayahTerdampak }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">dari <span class="font-semibold text-gray-600">{{ $totalWilayah }}</span> wilayah</p>
                        <div class="mt-3 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-400 to-rose-500 rounded-full transition-all"
                                 style="width: {{ $totalWilayah > 0 ? round($wilayahTerdampak / $totalWilayah * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3 p-2.5 bg-red-50 rounded-xl group-hover:bg-red-100 transition-colors">
                        <x-lucide-map-pin class="w-5 h-5 text-red-500" />
                    </div>
                </div>
            </a>

            {{-- Sanitasi Bermasalah --}}
            <a href="{{ route('manajemen-data.sanitasi.index') }}"
               class="group bg-white rounded-xl border border-gray-100 shadow-stat hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200 p-5 overflow-hidden relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-orange-400 to-amber-600 rounded-l-xl"></div>
                <div class="flex items-start justify-between pl-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest">Sanitasi Bermasalah</p>
                        <p class="text-3xl font-black text-gray-900 mt-1.5 leading-none">{{ $sanitasiRusak + $sanitasiTidakAda }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">
                            <span class="font-semibold text-orange-600">{{ $sanitasiRusak }}</span> rusak ·
                            <span class="font-semibold text-gray-600">{{ $sanitasiTidakAda }}</span> tidak ada
                        </p>
                        <div class="mt-3 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                            @php $totalSanitasi = $sanitasiBaik + $sanitasiRusak + $sanitasiTidakAda; @endphp
                            <div class="h-full bg-gradient-to-r from-orange-400 to-amber-500 rounded-full transition-all"
                                 style="width: {{ $totalSanitasi > 0 ? round(($sanitasiRusak + $sanitasiTidakAda) / $totalSanitasi * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3 p-2.5 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition-colors">
                        <x-lucide-alert-triangle class="w-5 h-5 text-orange-500" />
                    </div>
                </div>
            </a>

            {{-- Distribusi Bulan Ini --}}
            <a href="{{ route('penyaluran-air.index') }}"
               class="group bg-white rounded-xl border border-gray-100 shadow-stat hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200 p-5 overflow-hidden relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-indigo-600 rounded-l-xl"></div>
                <div class="flex items-start justify-between pl-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest">Distribusi Air Bulan Ini</p>
                        <p class="text-3xl font-black text-gray-900 mt-1.5 leading-none">{{ number_format($distribusiBulanIni / 1000, 1) }}<span class="text-base font-semibold text-gray-500">m³</span></p>
                        <p class="text-xs text-gray-400 mt-1.5">≈ {{ number_format($distribusiBulanIni) }} liter</p>
                        <div class="mt-3 flex items-center gap-1">
                            <x-lucide-trending-up class="w-3.5 h-3.5 text-blue-400" />
                            <span class="text-xs text-blue-500 font-medium">Bulan berjalan</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3 p-2.5 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">
                        <x-lucide-droplets class="w-5 h-5 text-blue-500" />
                    </div>
                </div>
            </a>

            {{-- Menunggu Distribusi --}}
            <a href="{{ route('penyaluran-air.index') }}"
               class="group bg-white rounded-xl border border-gray-100 shadow-stat hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200 p-5 overflow-hidden relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-sky-400 to-blue-600 rounded-l-xl"></div>
                <div class="flex items-start justify-between pl-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest">Belum Terdistribusi</p>
                        <p class="text-3xl font-black text-gray-900 mt-1.5 leading-none">{{ $totalPending }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">tugas menunggu aksi</p>
                        <div class="mt-3 flex items-center gap-1">
                            @if($totalPending > 0)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                                    <x-lucide-clock class="w-3 h-3" />
                                    Perlu konfirmasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                    <x-lucide-check-circle class="w-3 h-3" />
                                    Semua selesai
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3 p-2.5 bg-sky-50 rounded-xl group-hover:bg-sky-100 transition-colors">
                        <x-lucide-package class="w-5 h-5 text-sky-600" />
                    </div>
                </div>
            </a>

        </div>

        {{--  Baris Tengah: Chart + Status Sanitasi  --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Chart Volume Distribusi --}}
            <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-5"
                 x-data="chartDistribusiData()"
                 x-init="init()">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Volume Distribusi Air</h3>
                        <p class="text-xs text-gray-500" x-text="periodeLabel"></p>
                    </div>
                    {{-- Filter Tabs --}}
                    <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-0.5 self-start sm:self-auto">
                        <button x-on:click="setPeriode('7d')" type="button"
                                :class="periode === '7d' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                                class="px-2.5 py-1 rounded-md text-xs font-medium transition-all">7 Hari</button>
                        <button x-on:click="setPeriode('30d')" type="button"
                                :class="periode === '30d' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                                class="px-2.5 py-1 rounded-md text-xs font-medium transition-all">30 Hari</button>
                        <button x-on:click="setPeriode('6m')" type="button"
                                :class="periode === '6m' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                                class="px-2.5 py-1 rounded-md text-xs font-medium transition-all">6 Bulan</button>
                    </div>
                </div>
                <div class="h-48 sm:h-52 relative">
                    <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 z-10">
                        <div class="animate-spin w-6 h-6 border-2 border-indigo-500 border-t-transparent rounded-full"></div>
                    </div>
                    <canvas id="chartDistribusi"></canvas>
                </div>
            </div>

            {{-- Breakdown Sanitasi  --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Status Sanitasi</h3>
                        <p class="text-xs text-gray-500">Proporsi kondisi fasilitas</p>
                    </div>
                    <x-lucide-pie-chart class="w-5 h-5 text-gray-400" />
                </div>
                <div class="h-36 flex items-center justify-center">
                    <canvas id="chartSanitasi"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span> Baik</span>
                        <span class="font-semibold text-gray-700">{{ $sanitasiBaik }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Rusak</span>
                        <span class="font-semibold text-gray-700">{{ $sanitasiRusak }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span> Tidak Ada</span>
                        <span class="font-semibold text-gray-700">{{ $sanitasiTidakAda }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{--  Baris Bawah: Pending + Laporan  --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Penyaluran Pending  --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Distribusi Menunggu Konfirmasi</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Klik "Konfirmasi" untuk tandai sudah terdistribusi</p>
                    </div>
                    <a href="{{ route('penyaluran-air.index') }}" class="text-xs text-indigo-600 hover:underline font-medium whitespace-nowrap ml-3">Lihat Semua →</a>
                </div>

                @if($penyaluranPending->isEmpty())
                    <div class="p-8 text-center">
                        <x-lucide-check-circle class="w-10 h-10 text-green-400 mx-auto mb-2" />
                        <p class="text-sm text-gray-500">Semua distribusi sudah terkonfirmasi!</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($penyaluranPending as $item)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-5 py-3.5 gap-2 hover:bg-gray-50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $item->wilayah?->nama ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->sumber_air }} ·
                                        {{ number_format($item->volume_liter) }} L ·
                                        {{ $item->tanggal_distribusi?->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="sm:ml-3 flex-shrink-0">
                                    <button type="button"
                                            @click="$dispatch('open-confirm-modal', {
                                                title: 'Konfirmasi Distribusi',
                                                message: 'Apakah Anda yakin ingin menandai distribusi ini sudah <strong>terkonfirmasi</strong>?',
                                                action: '{{ route('penyaluran-air.status', $item->id) }}?status=terdistribusi',
                                                method: 'PATCH',
                                                type: 'success',
                                                confirmText: 'Ya, Konfirmasi'
                                            })"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                        <x-lucide-check class="w-3.5 h-3.5" />
                                        Konfirmasi
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Laporan Kondisi Terbaru  --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Laporan Kondisi Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">5 inspeksi terakhir oleh petugas</p>
                    </div>
                    <a href="{{ route('laporan-kondisi.index') }}" class="text-xs text-indigo-600 hover:underline font-medium whitespace-nowrap ml-3">Lihat Semua →</a>
                </div>

                @if($laporanTerbaru->isEmpty())
                    <div class="p-8 text-center">
                        <x-lucide-clipboard-list class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                        <p class="text-sm text-gray-500">Belum ada laporan kondisi.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($laporanTerbaru as $laporan)
                            <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors">
                                <div class="mt-0.5 flex-shrink-0 w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                                    <x-lucide-clipboard-check class="w-4 h-4 text-indigo-500" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $laporan->wilayah?->nama ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-1">{{ $laporan->catatan ?? 'Tidak ada catatan' }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs text-gray-400">{{ $laporan->tanggal_inspeksi?->format('d M') }}</p>
                                    <p class="text-xs text-gray-500">{{ $laporan->petugas?->name ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Define Alpine component globally to avoid init timing issues
        window.chartDistribusiData = () => ({
            periode: '6m',
            loading: false,
            get periodeLabel() {
                return { '7d': '7 hari terakhir', '30d': '30 hari terakhir', '6m': '6 bulan terakhir' }[this.periode] + ' (liter)';
            },
            init() {
                const canvas = document.getElementById('chartDistribusi');
                if (!canvas) return;

                const existing = Chart.getChart('chartDistribusi');
                if (existing) existing.destroy();

                new Chart(canvas, {
                    type: 'bar',
                    data: { labels: [], datasets: [{
                        label: 'Volume (L)',
                        data: [],
                        backgroundColor: 'rgba(99,102,241,0.15)',
                        borderColor: 'rgb(99,102,241)',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]},
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' },
                                 ticks: { callback: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v } }
                        }
                    }
                });
                this.fetchChart();
            },
            async setPeriode(p) {
                this.periode = p;
                await this.fetchChart();
            },
            async fetchChart() {
                this.loading = true;
                try {
                    const res = await fetch(`{{ route('dashboard.chart-data') }}?periode=${this.periode}`);
                    const json = await res.json();
                    const chart = Chart.getChart('chartDistribusi');
                    if (chart) {
                        chart.data.labels = json.labels;
                        chart.data.datasets[0].data = json.data;
                        chart.update();
                    }
                } catch (e) {
                    console.error('Failed to fetch chart data:', e);
                } finally {
                    this.loading = false;
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const existingDonut = Chart.getChart('chartSanitasi');
            if (existingDonut) existingDonut.destroy();
            const canvas = document.getElementById('chartSanitasi');
            if (!canvas) return;

            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: ['Baik', 'Rusak', 'Tidak Ada'],
                    datasets: [{
                        data: [{{ $sanitasiBaik }}, {{ $sanitasiRusak }}, {{ $sanitasiTidakAda }}],
                        backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
