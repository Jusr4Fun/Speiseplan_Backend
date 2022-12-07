<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'email' => $this->email,
          'role' => $this->rollen->role_code,
          'role_id' => $this->role_id,
          'abteilung_id' => $this->abteilung_id,
          'abteilung' => $this->abteilung->name,
          //'emailVerified' => $this->email_verified_at,
        ];
    }
}