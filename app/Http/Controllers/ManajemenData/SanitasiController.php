<?php

namespace App\Http\Controllers\ManajemenData;

use App\Models\Sanitasi;
use App\Models\Wilayah;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ManajemenData\Sanitasi\Store;
use App\Http\Requests\ManajemenData\Sanitasi\Update;

class SanitasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Sanitasi::with('wilayah');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhereHas('wilayah', fn($w) => $w->where('nama', 'like', "%{$search}%"));
                });
            }

            if ($request->filled('wilayah_id')) {
                $query->where('wilayah_id', $request->wilayah_id);
            }

            if ($request->filled('sort')) {
                $query->orderBy($request->sort, $request->dir ?? 'asc');
            } else {
                $query->orderBy('nama');
            }

            $data = $query->paginate($request->limit ?? 10);

            return $this->success('Sanitasi berhasil diambil', $data->items(), $data->total());
        }

        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama']);
        return view('manajemen-data.sanitasi.index', compact('wilayahs'));
    }

    public function create()
    {
        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama', 'kecamatan']);

        // Transform data
        $wilayahs = $wilayahs->map(function ($wilayah) {
            return [
                'id' => $wilayah->id,
                'nama' => $wilayah->nama . ' (' . $wilayah->kecamatan . ')',
            ];
        });
        return view('manajemen-data.sanitasi.create', compact('wilayahs'));
    }

    public function store(Store $request)
    {
        Sanitasi::create($request->validated());

        return redirect()->route('manajemen-data.sanitasi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Produk sanitasi berhasil ditambahkan',
            ]);
    }

    public function edit(Sanitasi $sanitasi)
    {
        $wilayahs = Wilayah::orderBy('nama')->get(['id', 'nama', 'kecamatan']);

        // Transform data
        $wilayahs = $wilayahs->map(function ($wilayah) {
            return [
                'id' => $wilayah->id,
                'nama' => $wilayah->nama . ' (' . $wilayah->kecamatan . ')',
            ];
        });
        return view('manajemen-data.sanitasi.edit', compact('sanitasi', 'wilayahs'));
    }

    public function update(Update $request, Sanitasi $sanitasi)
    {
        $sanitasi->update($request->validated());

        return redirect()->route('manajemen-data.sanitasi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Produk sanitasi berhasil diupdate',
            ]);
    }

    public function destroy(Sanitasi $sanitasi)
    {
        $sanitasi->delete();

        return redirect()->route('manajemen-data.sanitasi.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Produk sanitasi berhasil dihapus',
            ]);
    }
}
