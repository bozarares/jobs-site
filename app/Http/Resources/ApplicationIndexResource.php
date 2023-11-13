<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'job' => [
                'id' => $this->job->id,
                'slug' => $this->job->slug,
                'title' => $this->job->title,
                'salary' => $this->job->salary,
                'location' => $this->job->location,
                'levels' => $this->job->levels,
                'type' => $this->job->type,
                'company' => CompanyMinimalResource::make($this->job->company),
            ],
        ];
    }
}
