<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
class UserFilesController extends Controller
{
    public function addFile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'servername' => 'required|string',
            'extension' => 'required|string|in:pdf',
        ]);
        $user = $request->user();
        $userFile = $user->files()->create([
            'name' => $request->name,
            'extension' => $request->extension,
            'servername' => $request->servername,
        ]);
        return response()->json(
            [
                'success' => true,
                'message' => 'File added successfully',
                'file' => $userFile,
            ],
            200
        );
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $user = $request->user();
        $userFile = $user->files()->findOrFail($request->id);
        $userFile->delete();
        return response()->json(
            ['success' => true, 'message' => 'File deleted successfully'],
            200
        );
    }

    public function show(Request $request, $path)
    {
        $user = $request->user();
        $userFile = $user
            ->files()
            ->where('servername', $path)
            ->firstOrFail();

        $filename = $userFile->name . '.' . $userFile->extension;
        if (!Storage::disk('userFiles')->exists($userFile->servername)) {
            return redirect()->route('welcome');
        }

        $headers = [
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ];

        return Storage::disk('userFiles')->response($path, null, $headers);
    }
    public function download(Request $request, $path)
    {
        $user = $request->user();
        $userFile = $user
            ->files()
            ->where('servername', $path)
            ->firstOrFail();

        $filename = $userFile->name . '.' . $userFile->extension;
        if (!Storage::disk('userFiles')->exists($userFile->servername)) {
            return redirect()->route('welcome');
        }

        $headers = [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Storage::disk('userFiles')->response($path, null, $headers);
    }
}
