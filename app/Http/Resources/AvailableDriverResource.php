<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableDriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'driver' => $this->whenLoaded('driver', function () {
                return $this->driver;
            }),
            'car' => $this->whenLoaded('car'),

            // 'kota_asal_id' => $this->kota_asal_id,
            // 'kota_asal' => $this->whenLoaded('kota_asal'),
            // 'kota_tujuan_id' => $this->kota_tujuan_id,
            // 'kota_tujuan' => $this->whenLoaded('kota_tujuan'),
            'time_departure' => $this->time_departure,

        ];

    }
}
