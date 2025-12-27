<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'time' => $this->resource['status_code'] ?? LARAVEL_START - microtime(true),
            'status_code' => $this->resource['status_code'] ?? 503,
            'status_message' => $this->resource['status_message'] ?? 'Oops! Something went wrong.',
            'result' => $this->resouce['result'] ?? null,
        ];
    }
}
