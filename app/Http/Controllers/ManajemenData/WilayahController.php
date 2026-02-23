<?php

namespace App\Http\Controllers\ManajemenData;

use App\Models\Wilayah;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ManajemenData\Wilayah\Store;
use App\Http\Requests\ManajemenData\Wilayah\Update;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Wilayah::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('kecamatan', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('sort')) {
                $query->orderBy($request->sort, $request->dir ?? 'asc');
            } else {
                $query->orderBy('nama');
            }

            $data = $query->paginate($request->limit ?? 10);

            return $this->success('Wilayah berhasil diambil', $data->items(), $data->total());
        }
        return view('manajemen-data.wilayah.index');
    }

    public function create()
    {
        return view('manajemen-data.wilayah.create');
    }

    public function store(Store $request)
    {
        $data = $request->validated();

        Wilayah::create($data);

        return redirect()->route('manajemen-data.wilayah.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Wilayah berhasil ditambahkan',
            ]);
    }

    public function edit(Wilayah $wilayah)
    {
        return view('manajemen-data.wilayah.edit', compact('wilayah'));
    }

    public function update(Update $request, Wilayah $wilayah)
    {
        $data = $request->validated();

        $wilayah->update($data);

        return redirect()->route('manajemen-data.wilayah.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Wilayah berhasil diupdate',
            ]);
    }

    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();

        return redirect()->route('manajemen-data.wilayah.index')
            ->with('notification', [
                'type'    => 'success',
                'title'   => 'Berhasil',
                'message' => 'Wilayah berhasil dihapus',
            ]);
    }
}
