<?php

namespace App\Http\Controllers;

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
        ]);

        $organizerId = $request->input('organizer_id');
        $packageId = $request->input('package_id');

        // Simpan file ke storage/app/public/uploads/{organizerId}/packages/{packageId}/
        $path = $request->file('image')->store("uploads/$organizerId/packages/$packageId", 'public');

        // Kembalikan URL untuk blade
        $url = asset('storage/' . $path);

        return back()->with('success', 'Gambar berjaya diupload')->with('url', $url);
    }
}
