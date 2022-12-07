<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Abteilung;

class UserFactory extends Factory
{
    public function definition()
    {
        $abteilungen = Abteilung::pluck('id')->toArray();
        return [
            'name' => fake()->name(),
            'abteilung_id' => fake()->randomElement($abteilungen),
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role_id' => 2
        ];
    }

}
