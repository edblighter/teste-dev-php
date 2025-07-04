<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            "street" => $this->street,
            "post_code" => $this->post_code ,
            "state" => $this->state,
            "city" => $this->city,
            "country" => $this->country,
          //  'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
          //  'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
        ];
    }
}
