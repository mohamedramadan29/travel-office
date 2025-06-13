<?php

namespace Database\Factories;

use App\Models\admin\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;


    public function definition(): array
    {
        $country = Country::inRandomOrder()->first();
        if(!$country){
            throw new \Exception('Country not found');
        }
        $governrate = $country->governorates()->inRandomOrder()->first();
        if(!$governrate){
            throw new \Exception('Governrate not found');
        }
        // $city = $governrate->cities()->inRandomOrder()->first();
        // if(!$city){
        //     throw new \Exception('City not found');
        // }
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'is_active' => 1,
            'mobile' => fake()->phoneNumber(),
            'country_id' => $country->id,
            'governrate_id' => $governrate->id,
            'city_id' => random_int(1,4),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
