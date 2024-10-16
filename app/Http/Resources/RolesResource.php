<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Success',
            'status' => 200,
            'type' => 'roles',
            'count' => ($this->count()),
            // get books with authors
            'books' => $this->resource->toArray(),
        ];
    }
}
