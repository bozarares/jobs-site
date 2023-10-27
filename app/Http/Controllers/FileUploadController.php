<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        Log::info('Processing file...');
        // $request->validate([
        //     'file' => 'required|image|max:2048',
        // ]);
        /** @var UploadedFile[] */
        $files = $request->allFiles();
        Log::info('All files', $files);

        if (empty($files)) {
            abort(422, 'No file was uploaded');
        }

        if (count($files) > 1) {
            abort(422, 'Only one file can be uploaded at a time');
        }

        $requestKey = array_key_first($files);

        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        Log::info('File details: ' . print_r($file, true));
        $hashedName = hash(
            'sha256',
            $file->getClientOriginalName() . now()->timestamp
        );

        $file_path = $file->storeAs(
            'tmp/',
            $hashedName . '.' . $file->getClientOriginalExtension()
        );

        Log::info('File path: ' . $file_path);
        return $hashedName;
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'extension' => 'required|string',
        ]);
        $filename = $request->filename . '.' . $request->extension;
        Log::info('Deleting file: ' . $filename);
        if (!$filename) {
            return response()->json(['message' => 'File not provided'], 400);
        }
        $filePath = storage_path('app/tmp/' . $filename);
        Log::info('File path: ' . $filePath);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $parentDir = dirname($filePath);
            if (
                is_dir($parentDir) &&
                !(new \FilesystemIterator($parentDir))->valid()
            ) {
                rmdir($parentDir);
            }
            return response()->json(
                ['success' => true, 'message' => 'File deleted successfully'],
                200
            );
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }
}
