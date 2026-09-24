<?php

namespace App\Http\Controllers;

use App\Models\FrameTemplate;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FrameTemplateController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('frames', 'result');

        return response()->json([
            'message' => 'File berhasil diupload',
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }
}