<?php

namespace App\Models;

//use App\Services\VimeoService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasUuids;

    protected $guarded = [
        'id',

    ];

    protected $casts = [
        'can_preview' => 'boolean',
        'is_published' => 'boolean',
        'duration' => 'integer',
    ];

    protected $appends = ['video_url'];

    protected static function booted()
    {
        static::deleting(function (Lesson $lesson) {
//            if ($lesson->video_driver === 'vimeo' && $lesson->video_source) {
//                App::make(VimeoService::class)->delete($lesson->video_source);
//            }
            // Add cleanup for local/s3 files if needed
            if ($lesson->video_driver === 'local' && $lesson->video_source) {
                Storage::disk(config('filesystems.default'))->deleteDirectory("lessons/{$lesson->title}");
            }
        });
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    protected function getVideoUrlAttribute(): ?string
    {
        // add video statuas check
        if ($this->status !== 'ready') {
            return null;
        }
        if ($this->is_published !== 'true') {
            return null;
        }

        if ($this->video_driver === 'vimeo') {
            return "https://player.vimeo.com/video/{$this->video_source}";
        }

        if ($this->video_driver === 'file_system') {
            return Storage::disk(config('filesystems.default'))->path($this->video_source);

        }

        return null;

    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->duration ? gmdate('H:i:s', $this->duration) : '00:00:00',
        );
    }

    // TODO: to check if the lesson is free or,we would check if the user is a subscribed user. If yes, return true, if no check the record on the database

    public function updateVideoStatus(): void
    {

//        if ($this->video_driver === 'vimeo') {
//            $status = App::make(VimeoService::class)->getStatus($this->video_source);
//
//            if ($this->status !== $status) {
//                $this->update(['status' => $status]);
//            }
//        }

        if ($this->video_driver === 'file_system' && $this->status !== 'ready') {
            // Local videos are processed immediately, so they should be ready
            $this->update(['status' => 'ready']);
        }
    }
}
