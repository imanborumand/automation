<?php namespace App\Http\Resources\Document;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class DocumentResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'priority' => $this->getPriority(),
            'status' => $this->status,
        ];
    }


    /**
     * @return string
     */
    private function getPriority() : string
    {
        return match ($this->priority) {
             '1' => 'NORMAL',
             '2' => 'IMPORTANT',
             '3' => 'NECESSARY',
        };
    }
}
