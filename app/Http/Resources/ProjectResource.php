<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'project_name' => $this->project_name,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'start_date' => $this->start_date->toDateString(),
            'due_date' => $this->due_date->toDateString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];

        
    }
}