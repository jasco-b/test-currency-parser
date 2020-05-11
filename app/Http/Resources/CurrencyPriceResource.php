<?php

namespace App\Http\Resources;

use App\Models\CurrencyPrice;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CurrencyPriceResource
 * @package App\Http\Resources
 * @mixin CurrencyPrice
 */
class CurrencyPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'valuteID' => $this->currency->valuteID,
            'charCode' => $this->currency->charCode,
            'numCode' => $this->currency->numCode,
            'value' => $this->value,
            'nominal' => $this->nominal,
            'date' => $this->date->format('d.m.Y'),
            'created' => $this->created_at
        ];
    }
}
