<?php

namespace App\Http\Controllers;

use App\Models\LaporanKondisi;
use App\Models\Wilayah;
use App\Http\Requests\LaporanKondisi\Store;
use App\Http\Requests\LaporanKondisi\Update;
use App\Exports\LaporanKondisiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKondisiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = LaporanKondisi::with(['wilayah', 'petugas']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('catatan', 'like', "%{$search}%")
                        ->orWhereHas('wilayah', fn($w) => $w->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('petugas', fn($p) => $p->where('name', 'like', "%{$search}%"));
                });
            }

            if ($request->filled('wilayah_id')) {
                $query->where('wilayah_id', $request->wilayah_id);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('tanggal_inspeksi', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('tanggal_inspeksi', '<=', $request->end_date);
            }

            if ($request->filled('sort_by')) {
                $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
            } else {
                $query->latest('tanggal_inspeksi');
            }

            $data = $query->paginate($request->limit ?? 10);

            return $this->success('Data laporan kondisi berhasil diambil', $data->items(), $data->total());
        }

        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama']);
        return view('laporan-kondisi.index', compact('wilayahs'));
    }

    public function create()
    {
        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama', 'kecamatan'])
            ->map(fn($w) => ['id' => $w->id, 'nama' => $w->nama . ' (' . $w->kecamatan . ')']);
        return view('laporan-kondisi.create', compact('wilayahs'));
    }

    public function store(Store $request)
    {
        LaporanKondisi::create([
            ...$request->validated(),
            'petugas_id' => auth()->id(),
        ]);

        return redirect()->route('laporan-kondisi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Laporan kondisi berhasil ditambahkan',
            ]);
    }

    public function edit(LaporanKondisi $laporanKondisi)
    {
        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama', 'kecamatan'])
            ->map(fn($w) => ['id' => $w->id, 'nama' => $w->nama . ' (' . $w->kecamatan . ')']);
        return view('laporan-kondisi.edit', compact('laporanKondisi', 'wilayahs'));
    }

    public function update(Update $request, LaporanKondisi $laporanKondisi)
    {
        $laporanKondisi->update($request->validated());

        return redirect()->route('laporan-kondisi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Laporan kondisi berhasil diupdate',
            ]);
    }

    public function destroy(LaporanKondisi $laporanKondisi)
    {
        $laporanKondisi->delete();

        return redirect()->route('laporan-kondisi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Laporan kondisi berhasil dihapus',
            ]);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['wilayah_id', 'start_date', 'end_date']);
        $filename = 'laporan-kondisi-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new LaporanKondisiExport($filters), $filename);
    }
}
