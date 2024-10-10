<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,doc,docx,pdf'
        ]);

        // $path = $request->file('file')->store('attachments', 'public');

        // return response()->json(['url' => Storage::url($path)]);

        // Upload the image if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $updatedFilename = time() . rand(10000, 9999999) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/attachments/');
            $file->move($destinationPath, $updatedFilename);
        } else {
            // If image not provided, set to null
            $updatedFilename = null;
        }

        // path
        $path = '/public/images/attachments/' . $updatedFilename;
        return response()->json(['url' => $path]);
    }
}
