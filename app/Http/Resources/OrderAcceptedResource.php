<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAcceptedResource extends JsonResource
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
            'passenger_name' => $this->user->name,
            'kota_asal' => $this->driverDeparture->kotaAsal->nama_kota,
            'kota_tujuan' => $this->driverDeparture->kotaTujuan->nama_kota,
            'date_order' => $this->created_at->format('d M Y'),
            'status_order' => $this->status_order_id,
        ];

    }
}
