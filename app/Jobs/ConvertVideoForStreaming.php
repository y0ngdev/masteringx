<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lesson;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Lesson $lesson;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 5;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function handle(): void
    {
        $lowBitrateFormat = (new X264)->setKiloBitrate(500);
        $midBitrateFormat = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);
//add
        $p = 'lessons/' . $this->lesson->module->title . '/' . $this->lesson->title . '/' . $this->lesson->id . '.m3u8';
        FFMpeg::fromDisk(config('filesystems.default'))
            ->open($this->lesson->video_source)
            ->exportForHLS()
            ->toDisk(config('filesystems.default'))
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)
            ->save($p);

        Storage::delete($this->lesson->video_source);

        $this->lesson->update([
            'video_source' => $p,
            'status' => 'ready',
        ]);

    }
}
