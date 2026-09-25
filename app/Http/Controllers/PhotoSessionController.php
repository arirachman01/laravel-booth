<?php

namespace App\Http\Controllers;

use App\Models\PhotoResult;
use App\Models\PhotoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class PhotoSessionController extends Controller
{
    public function view()
    {
        $sessionId = request('session_id');

        $session = PhotoSession::with('results')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        return view('results', compact('session'));
    }

    #[OA\Get(
            path: "/api/photo-sessions",
            summary: "Get all photo sessions",
            tags: ["Photo Sessions"],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Success"
                )
            ]
        )]
    public function index()
    {
        $sessions = PhotoSession::with('results')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }

    #[OA\Post(
        path: "/api/photo-sessions",
        summary: "Create photo session with photo results",
        tags: ["Photo Sessions"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    type: "object",
                    required: ["session_id", "files"],
                    properties: [
                        new OA\Property(
                            property: "session_id",
                            type: "string",
                            example: "SESSION-001"
                        ),
                        new OA\Property(
                            property: "files",
                            description: "Photo files",
                            type: "array",
                            items: new OA\Items(
                                type: "string",
                                format: "binary"
                            ),
                            minItems: 1
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Session and photo results created"
            )
        ]
    )]
    public function store(Request $request)
    {
       $validated = $request->validate([
            'sessionId' => [
                'required',
                'string',
                'unique:photo_sessions,session_id',
            ],
            'files' => ['required', 'array'],
            'files.*' => [
                'required', 'file',
                'mimes:jpg,jpeg,png,gif,webp', 
                'max:102400'
            ],
        ]);
        
        $session = PhotoSession::create([
            'session_id' => $validated['sessionId'],
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('results', 'public');

            $url = asset('storage/' . $path);

            PhotoResult::create([
                'session_id' => $session->id,
                'filename' => $file->getClientOriginalName(),
                'url' => $url,
            ]);
        }
        return response()->json([
            'sessionId' => $session->id,
        ]);
    }

    #[OA\Get(
        path: "/api/photo-sessions/{sessionId}",
        summary: "Get photo session detail",
        tags: ["Photo Sessions"],
        parameters: [
            new OA\Parameter(
                name: "sessionId",
                description: "Session ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string"),
                example: "sesi-f6bc95dc"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            ),
            new OA\Response(
                response: 404,
                description: "Session not found"
            )
        ]
    )]
    public function show(string $sessionId)
    {
        $session = PhotoSession::with('results')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $session,
        ]);
    }

    #[OA\Delete(
        path: "/api/photo-sessions/{sessionId}",
        summary: "Delete photo session",
        tags: ["Photo Sessions"],
        parameters: [
            new OA\Parameter(
                name: "sessionId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Session deleted"
            )
        ]
    )]
    public function destroy(string $sessionId)
    {
        $session = PhotoSession::with('results')
            ->where('session_id', $sessionId)
            ->firstOrFail();

        foreach ($session->results as $result) {
            if ($result->url) {
                $path = parse_url($result->url, PHP_URL_PATH);

                if ($path) {
                    $path = preg_replace('#^/storage/#', '', $path);

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            // Hapus record result
            $result->delete();
        }

        // Hapus session
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Photo session berhasil dihapus',
        ]);
    }
}