<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDriverRejectedResource extends JsonResource
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
            'passenger_age' => $this->user->passenger->age_passenger,
            'kota_asal' => $this->driverDeparture->kotaAsal->nama_kota,
            'kota_tujuan' => $this->driverDeparture->kotaTujuan->nama_kota,
            'date_departure' => $this->driverDeparture->date_departure,
            'time_departure' => $this->driverDeparture->time_departure,
            'label_seat_car' => $this->labelSeatCars->pluck('label_seat')->toArray(),
        ];

    }
}
