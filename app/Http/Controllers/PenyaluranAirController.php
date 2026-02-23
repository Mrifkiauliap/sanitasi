<?php

namespace App\Http\Controllers;

use App\Models\PenyaluranAir;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Http\Requests\PenyaluranAir\Store;
use App\Http\Requests\PenyaluranAir\Update;

class PenyaluranAirController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = PenyaluranAir::with('wilayah');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('sumber_air', 'like', "%{$search}%")
                        ->orWhereHas('wilayah', fn($w) => $w->where('nama', 'like', "%{$search}%"));
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('wilayah_id')) {
                $query->where('wilayah_id', $request->wilayah_id);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('tanggal_distribusi', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('tanggal_distribusi', '<=', $request->end_date);
            }

            if ($request->filled('sort')) {
                $query->orderBy($request->sort, $request->dir ?? 'asc');
            } else {
                $query->latest('tanggal_distribusi');
            }

            $data = $query->paginate($request->limit ?? 10);

            return $this->success('Data penyaluran air berhasil diambil', $data->items(), $data->total());
        }

        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama']);
        return view('penyaluran-air.index', compact('wilayahs'));
    }

    public function create()
    {
        $wilayahs = $this->getWilayahOptions();
        return view('penyaluran-air.create', compact('wilayahs'));
    }

    public function store(Store $request)
    {
        PenyaluranAir::create($request->validated());

        return redirect()->route('penyaluran-air.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Data penyaluran air berhasil ditambahkan',
            ]);
    }

    public function edit(PenyaluranAir $penyaluranAir)
    {
        $wilayahs = $this->getWilayahOptions();
        return view('penyaluran-air.edit', compact('penyaluranAir', 'wilayahs'));
    }

    public function update(Update $request, PenyaluranAir $penyaluranAir)
    {
        $penyaluranAir->update($request->validated());

        return redirect()->route('penyaluran-air.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Data penyaluran air berhasil diupdate',
            ]);
    }

    public function destroy(PenyaluranAir $penyaluranAir)
    {
        $penyaluranAir->delete();

        return redirect()->route('penyaluran-air.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Data penyaluran air berhasil dihapus',
            ]);
    }

    public function updateStatus(Request $request, PenyaluranAir $penyaluranAir)
    {
        $request->validate([
            'status' => 'required|in:terdistribusi,belum terdistribusi',
        ]);

        $penyaluranAir->update(['status' => $request->status]);

        return back()->with('notification', [
            'type'    => 'success',
            'title'   => 'Status Diperbarui',
            'message' => "Status penyaluran air berhasil diubah ke " . $request->status . ".",
        ]);
    }

    private function getWilayahOptions(): \Illuminate\Support\Collection
    {
        return Wilayah::orderBy('nama')->get(['id', 'nama', 'kecamatan'])
            ->map(fn($w) => ['id' => $w->id, 'nama' => $w->nama . ' (' . $w->kecamatan . ')']);
    }
}
