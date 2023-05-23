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
            'car_plate_number' => $this->driverDeparture->car->license_plate_number,
        ];
    }
}
