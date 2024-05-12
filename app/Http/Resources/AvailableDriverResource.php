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
        $fullNameDriver = $this->driver->user_driver->name;
        $name = explode(" ", $fullNameDriver);
        $driverName = $name[0];

        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'driver_name' => $driverName,
            'car_merk' => $this->car->merk,
            'car_prod_year' => $this->car->production_year,
            'car_seat_capacity' => $this->car->seating_capacity,
            'is_smoking' => $this->Driver->is_smoking === 0 ? 'Tidak Merokok' : 'Merokok',
        ];
    }
}
