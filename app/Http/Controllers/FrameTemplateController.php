<?php

namespace App\Http\Controllers;

use App\Models\FrameTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class FrameTemplateController extends Controller{
    #[OA\Get(
        path: '/api/frame-templates',
        tags: ['Frame Templates'],
        summary: 'Get all frame templates',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar frame berhasil diambil'
            ),
        ]
    )]
    public function index()
    {
        $frames = FrameTemplate::latest()->get();

        return response()->json([
            'message' => 'Daftar frame berhasil diambil',
            'data' => $frames,
        ]);
    }

    #[OA\Post(
        path: '/api/frame-templates',
        tags: ['Frame Templates'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['name', 'file'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Frame Astarte'
                        ),
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary'
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Frame berhasil diupload'
            ),
            new OA\Response(
                response: 422,
                description: 'Validasi gagal'
            ),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('frames', 'public');

        $frame = FrameTemplate::create([
            'name' => $validated['name'],
            'file_path' => $path,
            'file_url' => asset('storage/' . $path),
        ]);

        return response()->json([
            'message' => 'Frame berhasil diupload',
            'data' => $frame,
        ], 201);
    }


    #[OA\Get(
        path: '/api/frame-templates/{frameTemplate}',
        tags: ['Frame Templates'],
        summary: 'Get frame template detail',
        parameters: [
            new OA\Parameter(
                name: 'frameTemplate',
                description: 'ID frame template',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail frame berhasil diambil'
            ),
            new OA\Response(
                response: 404,
                description: 'Frame tidak ditemukan'
            ),
        ]
    )]
    public function show(FrameTemplate $frameTemplate)
    {
        return response()->json([
            'message' => 'Detail frame berhasil diambil',
            'data' => $frameTemplate,
        ]);
    }

    #[OA\Post(
        path: '/api/frame-templates/{frameTemplate}',
        tags: ['Frame Templates'],
        summary: 'Update frame template',
        description: 'Gunakan POST dengan multipart/form-data agar dapat mengirim file baru.',
        parameters: [
            new OA\Parameter(
                name: 'frameTemplate',
                description: 'ID frame template',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Frame Astarte Updated'
                        ),
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary'
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Frame berhasil diupdate'
            ),
            new OA\Response(
                response: 404,
                description: 'Frame tidak ditemukan'
            ),
            new OA\Response(
                response: 422,
                description: 'Validasi gagal'
            ),
        ]
    )]
    public function update(Request $request, FrameTemplate $frameTemplate)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'file' => ['sometimes', 'file', 'max:10240'],
        ]);

        if (isset($validated['name'])) {
            $frameTemplate->name = $validated['name'];
        }

        if ($request->hasFile('file')) {
            // Hapus file lama
            if (
                $frameTemplate->file_path &&
                Storage::disk('public')->exists($frameTemplate->file_path)
            ) {
                Storage::disk('public')->delete($frameTemplate->file_path);
            }

            // Simpan file baru
            $path = $request->file('file')->store('frames', 'public');

            $frameTemplate->file_path = $path;
            $frameTemplate->file_url = asset('storage/' . $path);
        }

        $frameTemplate->save();

        return response()->json([
            'message' => 'Frame berhasil diupdate',
            'data' => $frameTemplate,
        ]);
    }

    #[OA\Delete(
        path: '/api/frame-templates/{frameTemplate}',
        tags: ['Frame Templates'],
        summary: 'Delete frame template',
        parameters: [
            new OA\Parameter(
                name: 'frameTemplate',
                description: 'ID frame template',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Frame berhasil dihapus'
            ),
            new OA\Response(
                response: 404,
                description: 'Frame tidak ditemukan'
            ),
        ]
    )]
    public function destroy(FrameTemplate $frameTemplate)
    {
        if (
            $frameTemplate->file_path &&
            Storage::disk('public')->exists($frameTemplate->file_path)
        ) {
            Storage::disk('public')->delete($frameTemplate->file_path);
        }
        $frameTemplate->delete();

        return response()->json([
            'message' => 'Frame berhasil dihapus',
        ]);
    }
}
