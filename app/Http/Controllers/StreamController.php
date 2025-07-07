<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamController extends Controller
{


    public function handle($path, Request $request): StreamedResponse

    {

//        $user = Auth::user();
//        $token = $request->query('token');


        $id = Str::before(Str::before($path, '_'), '.');

        $lesson = Lesson::where('id', $id)->firstOrFail();

//        if (!$lesson->isValidAccessToken($token, $user)) {
//            abort(403, 'Invalid or expired token');
//        }

        $videoBasePath = Str::beforeLast($lesson->video_source, '/');

        $file = basename($path);
        $absolute = Storage::disk(config('filesystems.default'))->path($videoBasePath . '/' . $file);

        if (!file_exists($absolute)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $contentType = match ($ext) {
            'm3u8' => 'application/vnd.apple.mpegurl',
            'ts' => 'video/mp2t',
            default => 'application/octet-stream',
        };


        return response()->stream(function () use ($absolute) {
            readfile($absolute);
        }, 200, [
            'Content-Type' => $contentType,
            'Content-Length' => filesize($absolute),
            'Cache-Control' => 'no-cache',
            'Accept-Ranges' => 'bytes',
            'Content-Disposition' => 'inline; filename="' . basename($absolute) . '"',
        ]);
    }

}
