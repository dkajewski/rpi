<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class Note extends JsonResource
{
    /**
     * Transform the resource into an array
     *
     * @param Request $request
     * @return array
     */
    #[ArrayShape(['id' => "mixed", 'description' => "mixed", 'start_at' => "false|int", 'end_at' => "false|int"])] public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'start_at' => strtotime($this->start_at),
            'end_at' => ($end = strtotime($this->end_at)) < 0 ? 0 : $end,
        ];
    }
}