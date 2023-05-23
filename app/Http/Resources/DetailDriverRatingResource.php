<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailDriverRatingResource extends JsonResource
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
            'driver_name' => $this->driver->user_driver->name,
            'driver_age' => $this->driver->driver_age,
            'car_type' => $this->car->type,
            'car_prod_year' => $this->car->production_year,
            'car_seat_capacity' => $this->car->seating_capacity,
            'driver_is_smoking' => $this->driver->is_smoking,
        ];

    }
}
