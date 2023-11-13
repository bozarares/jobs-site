<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    private function previousApplicationId()
    {
        // Logică pentru determinarea ID-ului anterior
    }

    private function nextApplicationId()
    {
        // Logică pentru determinarea ID-ului următor
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'application' => $this->resource,
            'previous' => $this->previousApplicationId(),
            'next' => $this->nextApplicationId(),
        ];
    }
}
