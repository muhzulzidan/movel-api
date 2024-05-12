<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailOrderPassengerResource extends JsonResource
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
            'date_order' => $this->created_at->format('d M Y'),
            'driver_name' => $this->driverDeparture->driver->user_driver->name,
            'plat_number' => $this->driverDeparture->car->license_plate_number,
            'car_type' => $this->driverDeparture->car->type,
            'car_prod_year' => $this->driverDeparture->car->production_year,
        ];

    }
}
