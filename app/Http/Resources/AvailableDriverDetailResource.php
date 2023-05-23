<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableDriverDetailResource extends JsonResource
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
            'driver_name' => $this->driver->user_driver->name,
            'car_merk' => $this->car->merk,
            'car_prod_year' => $this->car->production_year,
            'car_seat_capacity' => $this->car->seating_capacity,
            'is_smoking' => $this->Driver->is_smoking === 0 ? 'Tidak Merokok' : 'Merokok',
        ];
    }
}
