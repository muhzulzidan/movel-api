<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderPassengerResource extends JsonResource
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
            'kota_tujuan' => $this->driverDeparture->kotaTujuan->nama_kota,
            'driver_name' => $this->driverDeparture->driver->user_driver->name,
            'date_order' => $this->created_at->format('d M Y'),
        ];

    }
}
