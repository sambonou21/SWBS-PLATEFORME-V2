<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * @param  \App\Models\Portfolio  $this
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'service_type' => $this->service_type,
            'client_name' => $this->client_name,
            'image_url' => $this->image_path,
            'url' => $this->url,
        ];
    }
}