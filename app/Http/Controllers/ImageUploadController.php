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
            'image' => 'required|image|max:5120',
            'organizer_id' => 'required|integer',
            'package_id' => 'required|integer',
            'filename' => 'nullable|string', // optional nama custom tanpa extension
        ]);

        $organizerId = $request->input('organizer_id');
        $packageId = $request->input('package_id');

        $file = $request->file('image');

        // Get extension dari file
        $extension = $file->getClientOriginalExtension();

        // Nama file custom, fallback ke original name
        $name = $request->input('filename') 
            ? $request->input('filename') . '.' . $extension
            : $file->getClientOriginalName();

        // Simpan file ke storage dengan nama custom
        $path = $file->storeAs(
            "uploads/$organizerId/packages/$packageId", 
            $name, 
            'public'
        );

        $url = asset('storage/' . $path);

        return back()->with('success', 'Gambar berjaya diupload')->with('url', $url);
    }

}
