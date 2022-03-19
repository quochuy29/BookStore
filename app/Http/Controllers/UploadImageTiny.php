<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadImageTiny extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $uploadImg = $file->store('images', 'public', uniqid() . '-' . $request->file->getClientOriginalName());
        return json_encode(['location' => "/storage/$uploadImg"]);
    }
}
