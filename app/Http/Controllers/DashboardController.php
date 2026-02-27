<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Models\Sanitasi;
use App\Models\PenyaluranAir;
use App\Models\LaporanKondisi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        //  Statistik Ringkas
        $totalWilayah       = Wilayah::count();
        $wilayahTerdampak   = Wilayah::where('status', 'terdampak')->count();

        $totalProdukSanitasi = Sanitasi::sum('jumlah');

        $distribusiBulanIni = PenyaluranAir::whereMonth('tanggal_distribusi', now()->month)
                                ->whereYear('tanggal_distribusi', now()->year)
                                ->where('status', 'terdistribusi')
                                ->sum('volume_liter');

        $totalPending       = PenyaluranAir::where('status', 'belum terdistribusi')->count();

        //  5 Penyaluran Belum Terdistribusi (aksi cepat)
        $penyaluranPending  = PenyaluranAir::with('wilayah')
                                ->where('status', 'belum terdistribusi')
                                ->latest('tanggal_distribusi')
                                ->take(5)
                                ->get();

        //  5 Laporan Kondisi Terbaru
        $laporanTerbaru     = LaporanKondisi::with(['wilayah', 'petugas'])
                                ->latest('tanggal_inspeksi')
                                ->take(5)
                                ->get();

        //  Chart: Volume distribusi 6 bulan terakhir
        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M Y');
            $chartData[]   = PenyaluranAir::whereMonth('tanggal_distribusi', $bulan->month)
                                ->whereYear('tanggal_distribusi', $bulan->year)
                                ->sum('volume_liter');
        }

        return view('dashboard', compact(
            'totalWilayah',
            'wilayahTerdampak',
            'totalProdukSanitasi',
            'distribusiBulanIni',
            'totalPending',
            'penyaluranPending',
            'laporanTerbaru',
            'chartLabels',
            'chartData',
        ));
    }

    public function chartData(Request $request)
    {
        $periode = $request->get('periode', '6m');

        $labels = [];
        $data   = [];

        if ($periode === '7d') {
            for ($i = 6; $i >= 0; $i--) {
                $hari       = now()->subDays($i);
                $labels[]   = $hari->format('d M');
                $data[]     = PenyaluranAir::whereDate('tanggal_distribusi', $hari->toDateString())->sum('volume_liter');
            }
        } elseif ($periode === '30d') {
            for ($i = 29; $i >= 0; $i--) {
                $hari       = now()->subDays($i);
                $labels[]   = $hari->format('d/m');
                $data[]     = PenyaluranAir::whereDate('tanggal_distribusi', $hari->toDateString())->sum('volume_liter');
            }
        } else {
            // 6m default
            for ($i = 5; $i >= 0; $i--) {
                $bulan      = now()->subMonths($i);
                $labels[]   = $bulan->translatedFormat('M Y');
                $data[]     = PenyaluranAir::whereMonth('tanggal_distribusi', $bulan->month)
                                ->whereYear('tanggal_distribusi', $bulan->year)
                                ->sum('volume_liter');
            }
        }

        return response()->json(compact('labels', 'data'));
    }
}
