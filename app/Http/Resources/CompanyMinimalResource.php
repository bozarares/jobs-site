<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyMinimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'logo' => $this->logo,
            'logo_extension' => $this->logo_extension,
            'location' => [
                'country' => $this->country,
                'state' => $this->state,
                'town' => $this->town,
            ],
        ];
    }
}
