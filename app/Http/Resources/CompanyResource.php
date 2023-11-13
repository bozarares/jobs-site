<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'logo' => $this->logo,
            'logo_extension' => $this->logo_extension,
            'location' => [
                'country' => $this->country,
                'state' => $this->state,
                'town' => $this->town,
                'address' => $this->address,
            ],
            'contact' => [
                'phone_number' => $this->phone_number,
                'email' => $this->email,
            ],
            'description' => $this->description,
            'jobs' => JobMinimalResource::collection($this->jobs),
        ];
    }
}
