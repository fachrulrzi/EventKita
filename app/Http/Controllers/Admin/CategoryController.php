<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kategori', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|image|max:2048',
        ]);

        $iconPath = null;

        if ($request->hasFile('icon')) {
            // 🔥 Upload ke Railway Bucket
            $iconPath = $request->file('icon')->store(
                'categories',
                's3'
            );

            // 🔥 WAJIB: set visibility PUBLIC
            Storage::disk('s3')->setVisibility($iconPath, 'public');
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon_path' => $iconPath,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $kategori->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('icon')) {
            // hapus icon lama
            if ($kategori->icon_path) {
                Storage::disk('s3')->delete($kategori->icon_path);
            }

            // upload icon baru
            $newPath = $request->file('icon')->store(
                'categories',
                's3'
            );

            // 🔥 WAJIB PUBLIC
            Storage::disk('s3')->setVisibility($newPath, 'public');

            $data['icon_path'] = $newPath;
        }

        $kategori->update($data);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori): RedirectResponse
    {
        if ($kategori->icon_path) {
            Storage::disk('s3')->delete($kategori->icon_path);
        }

        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}