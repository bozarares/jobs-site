<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class JobMinimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $application = $this->whenLoaded('applications', function () {
            $firstApplication = $this->applications->first();

            return $firstApplication
                ? [
                    'application_date' => $firstApplication->created_at,
                    'status' => $firstApplication->status,
                    'seen_at' => $firstApplication->seen_at,
                ]
                : null;
        });

        $like_status = $this->whenLoaded('likes', function () {
            $like = $this->likes->first();
            return $like ? $like->like_status : 0;
        });
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'salary' => $this->salary,
            'location' => $this->location,
            'levels' => $this->levels,
            'type' => $this->type,
            'company' => CompanyMinimalResource::make(
                $this->whenLoaded('company')
            ),
            'application' => $application,
            'like_status' => $like_status,
        ];
    }
}
