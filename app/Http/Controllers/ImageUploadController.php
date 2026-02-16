<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    // Tunjuk form upload
    public function show()
    {
        return view('superadmin.upload-image'); // blade form
    }

    // Handle file upload
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // max 5MB
            'organizer_id' => 'required|integer',
            'package_id' => 'required|integer',
            'type' => 'required|in:package,slot', // pilih type
            'filename' => 'nullable|string', // optional nama custom tanpa extension
        ]);

        $organizerId = $request->input('organizer_id');
        $packageId = $request->input('package_id');
        $type = $request->input('type'); // package / slot
        $file = $request->file('image');

        $extension = $file->getClientOriginalExtension();
        $name = $request->input('filename') 
            ? $request->input('filename') . '.' . $extension
            : $file->getClientOriginalName();

        // Folder ikut type
        $folder = "uploads/$organizerId/$type/$packageId";

        $path = $file->storeAs($folder, $name, 'public');

        $url = asset('storage/' . $path);

        // Simpan session untuk display di blade (boleh loop banyak gambar nanti)
        $images = session()->get($type.'_images', []);
        $images[] = $url;
        session()->put($type.'_images', $images);

        return back()->with('success', ucfirst($type).' image berjaya diupload');
    }

}
