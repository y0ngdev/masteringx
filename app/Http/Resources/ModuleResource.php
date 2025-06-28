<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
//            'lessons' => LessonResource::collection($this->lessons),


        return $this->collection->map(function ($module) use ($request) {
            return [
                'id' => $module->id,
                'title' => $module->title,
                'lessons' => $module->lessons->map(function ($lesson) use ($request) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'url' => $lesson->video_url,
                        'canWatch' => $lesson->canWatch($request->user()),
                    ];
                }),
            ];
        })->all();
    }
}
