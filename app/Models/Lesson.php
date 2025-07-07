<?php

namespace App\Models;

//use App\Services\VimeoService;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasUuids;

    protected $guarded = [
        'id',

    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'can_preview' => 'boolean',
        'is_published' => 'boolean',
        'duration' => 'integer',
    ];



    protected $appends = ['stream_url'];

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

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * Get the stream URL for the lesson.
     *
     * This method returns the appropriate stream URL based on the video driver
     * and the status of the lesson.
     *
     * @return string
     */
    protected function getStreamUrlAttribute(): string
    {
        // Block access if video isn't ready or published
        if ($this->status !== 'READY' || !$this->is_published) {
            return '#';
        }
        if (!$this->canWatch(Auth::user())) {
            return '#';
        }

        //TODO        $token = $this->generateAccessToken();


        return match ($this->video_driver) {
            'VIMEO' => "https://player.vimeo.com/video/$this->video_source",
            'FILE' => route('watch.stream', [
                'path' => $this->id . '.m3u8',
//                'token' => $token,
            ]),
            default => '#',
        };
    }

    /**
     * Get the formatted duration of the lesson.
     *
     * @return Attribute
     */

    public function generateAccessToken(): string
    {
        return Crypt::encrypt([
            'user_id' => Auth::id(),
            'lesson_id' => $this->id,
            'expires' => now()->addMinutes(30)->timestamp,
        ]);
    }

    public function isValidAccessToken(string $token, ?User $user): bool
    {
        try {
            $data = Crypt::decrypt($token);
        } catch (\Exception $e) {
            return false;
        }

        return $data['user_id'] === $user->id
            && $data['lesson_id'] === $this->id
            && $data['expires'] >= now()->timestamp
            && $this->canWatch($user);
    }



    protected function duration(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => $value ? gmdate('H:i:s',$value) : '00:00:00',
        );
    }

    // TODO: to check if the lesson is free or,we would check if the user is a subscribed user. If yes, return true, if no check the record on the database

    /**
     * Update the video status based on the video driver.
     *
     * This method checks the status of the video based on the video driver
     * and updates the lesson's status accordingly.
     *
     * @return void
     */
    public function updateVideoStatus(): void
    {

//        if ($this->video_driver === 'vimeo') {
//            $status = App::make(VimeoService::class)->getStatus($this->video_source);
//
//            if ($this->status !== $status) {
//                $this->update(['status' => $status]);
//            }
//        }

        if ($this->video_driver === 'file' && $this->status !== 'READY') {
            // Local videos are processed immediately, so they should be ready
            $this->update(['status' => 'READY']);
        }
    }


    /**
     * Determine if the user can watch the lesson.
     *
     * @param User|null $user
     * @return bool|string
     */

    public function canWatch(?User $user): bool
    {
//        || !$user->subscribed()
        if (!$user) {
            return (bool)$this->can_preview;
        }

        return true;
    }

    /**
     * Scope the query to only include ready lessons.
     */
    #[Scope]
    protected function ready(Builder $query): void
    {
        $query->withAttributes([
            'status' => 'READY',
        ]);
    }

}
