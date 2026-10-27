<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
   
public function index(Request $request)
{
    $query = Kategori::query();

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_kategori', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    $kategori = $query->latest()->paginate(10);

    return view('admin.kategori.index', compact('kategori'));
}


    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['nama_kategori']);

        if (Kategori::where('slug', $slug)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'nama_kategori' => 'Nama kategori tersebut sudah digunakan.',
                ]);
        }

        $validated['slug'] = $slug;

        Kategori::create($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['nama_kategori']);

        $slugExists = Kategori::where('slug', $slug)
            ->where('id', '!=', $kategori->id)
            ->exists();

        if ($slugExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nama_kategori' => 'Slug kategori tersebut sudah digunakan.',
                ]);
        }

        $validated['slug'] = $slug;

        $kategori->update($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
