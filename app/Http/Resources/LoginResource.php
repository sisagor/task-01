<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 1,
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'token' => $this->createToken('auth_token')->plainTextToken,
            ]
        ];
    }
}
