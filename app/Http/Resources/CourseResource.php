<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category->name ?? null,
            'category_id' => $this->category_id,
            'instructor' => $this->instructor,
            'image' => $this->image ? asset($this->image) : null,
            'objectives' => json_decode($this->objectives),
            'course_content' => json_decode($this->course_content),
            'enrollments_count' => $this->enrollments()->count(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
