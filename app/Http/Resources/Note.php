<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Note extends JsonResource
{
    /**
     * Transform the resource into an array
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'start_at' => strtotime($this->start_at),
            'end_at' => ($end = strtotime($this->end_at)) < 0 ? 0 : $end,
        ];
    }
}