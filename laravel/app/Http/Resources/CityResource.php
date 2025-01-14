<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'is_favorite' => $this->pivot->is_favorite ?? false,
            'receive_forecasts' => $this->pivot->receive_forecasts ?? false,
        ];
    }
}

