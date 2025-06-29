<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StreamController extends Controller
{
    public function stream(Lesson $lesson): string
    {
        $path = Storage::disk(config('filesystems.default'))->path($lesson->video_source);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        $content = file_get_contents($path);

        // Rewrite .ts segment paths to use clean URLs
        $rewritten = preg_replace_callback(
            '/^(?!#)([^\/\n]+\.ts)(\?.*)?$/m',
            static fn($matches) => "/stream/$lesson->id/$matches[1]",
            $content
        );

        return response($rewritten, 200, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);

    }

    public function handle($path)

    {
        $id = Str::before(Str::before($path, '_'), '.');

        $lesson = Lesson::where('id', $id)->firstOrFail();


        $videoBasePath = Str::beforeLast($lesson->video_source, '/');
        // The actual requested file (e.g. uuid_0_500.m3u8, uuid_0_500_000.ts)
        $file = basename($path);
        $absolute = Storage::disk(config('filesystems.default'))->path($videoBasePath . '/' . $file);

        if (!file_exists($absolute)) {
            abort(401);
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
