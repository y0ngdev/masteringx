<?php

namespace App\Filament\Admin\Resources\LessonResource\Pages;

use App\Filament\Admin\Resources\LessonResource;
use App\Jobs\ConvertVideoForStreaming;
use Exception;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

//use App\Jobs\UploadLessonVideoToVimeo;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $duration = FFMpeg::fromDisk( $data['disk'])
                ->open($data['video'])
                ->getDurationInSeconds();

            if (!$duration) {
                $duration = $data['duration'] ?? 0;
            }
        } catch (Exception $e) {
            $duration = $data['duration'] ?? 0;
            Log::warning('Could not extract video duration: ' . $e->getMessage());
        }

        return $this->getModel()::create([
            "title" => $data['title'],
            "slug" => $data['slug'],
            "description" => $data['description'],
            "duration" => $duration,
            "can_preview" => $data['can_preview'],
            "position" => $data['position'],
            'video_source' => $data['video'],
            "is_published" => $data['is_published'],
            "module_id" => $data['module_id'],

        ]);
    }

    protected function afterCreate(): void
    {


        if (config('video.driver') === 'file_system') {
            ConvertVideoForStreaming::dispatch($this->getRecord());
        }
//        else {
//                UploadLessonVideoToVimeo::dispatch(
//                    $record->id,
//                    $videoPath,
//                    $record->title,
//                    $record->description ?? ''
//                );

//        }

    }
}
