<?php

namespace Database\Factories\admin;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\admin\Coupon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin\Coupon>
 */
class CouponFactory extends Factory
{

    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $limit = $this->faker->numberBetween(10, 100);
        $time_used = $this->faker->numberBetween(0, $limit);
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{5}'),
            'discount_percentage' => $this->faker->numberBetween(10, 50),
            'is_active' => $this->faker->boolean(),
            'start_date' => now()->addDays(random_int(1, 5)),
            'end_date' => now()->addDays(random_int(6, 30)),
            'limit' => $limit,
            'time_used' => $time_used,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
