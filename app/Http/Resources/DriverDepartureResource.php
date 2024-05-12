<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverDepartureResource extends JsonResource
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
            'message' => $this->message,
            'data' => [
                'id' => $this->id,
                'driver_id' => $this->driver_id,
                'kota_asal_id' => $this->kota_asal_id,
                'kota_tujuan_id' => $this->kota_tujuan_id,
                'date_departure' => $this->date_departure,
                'time_departure' => $this->time_departure,
            ],
        ];

    }
}
