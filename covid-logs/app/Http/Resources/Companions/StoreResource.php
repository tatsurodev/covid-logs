<?php

namespace App\Http\Resources\Companions;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'user_id' => $this->user_id,
            ],
            'links' => [
                'self' => $this->path(),
            ],
        ];
    }
}
