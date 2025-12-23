<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        // 🔥 UPLOAD KE S3 (MINIO)
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->storePublicly(
                'cities',
                's3'
            );
        }

        City::create($data);

        return redirect()->back()->with('success', 'Kota berhasil ditambahkan!');
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        // 🔥 UPDATE IMAGE KE S3
        if ($request->hasFile('image')) {

            // hapus image lama di S3
            if ($city->image_path) {
                Storage::disk('s3')->delete($city->image_path);
            }

            $data['image_path'] = $request->file('image')->storePublicly(
                'cities',
                's3'
            );
        }

        $city->update($data);

        return redirect()->back()->with('success', 'Kota berhasil diperbarui!');
    }

    public function destroy(City $city)
    {
        // 🔥 HAPUS IMAGE DI S3
        if ($city->image_path) {
            Storage::disk('s3')->delete($city->image_path);
        }

        $city->delete();

        return redirect()->back()->with('success', 'Kota berhasil dihapus!');
    }
}