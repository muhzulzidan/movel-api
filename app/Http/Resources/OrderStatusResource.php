<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
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
        'id_order' => $this->id,
        'status_order_id' => $this->statusOrder->id,
        'status_name' => $this->statusOrder->status_name,
        'driver_name' => $this->driverDeparture->driver->user_driver->name,
        'driver_email' => $this->driverDeparture->driver->user->email,
        'driver_id' => $this->driverDeparture->driver->user->id,
        'kota_asal' => $this->driverDeparture->kotaAsal->nama_kota,
        'kota_tujuan' => $this->driverDeparture->kotaTujuan->nama_kota,
        'car_plate_number' => $this->driverDeparture->car->license_plate_number,
        'driver_photo' => str_replace('/photos/public/', '/', $this->driverDeparture->driver->photo),
        'date_order' => $this->driverDeparture->date_departure,
        'time_order' => $this->driverDeparture->time_departure,
        'is_smoking_allowed' => $this->driverDeparture->driver->is_smoking,
    ];
    }
}
