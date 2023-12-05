<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Plan;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Membership $membership) {
            // ...
        })->afterCreating(function (Membership $membership) {
            // ...
            Attendance::factory()->count(random_int(1, 5))->create(
                [
                    'user_id' => $membership->user_id,
                ]
            );
        });
    }


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-6 months', 'now');
        $end = $this->faker->dateTimeBetween('+3 months', '+1 year');
        return [
            'plan_id' => Plan::all()->random()->id,
            'status' => 1,
            'start_date' => date_format($start, 'Y-m-d'),
            'end_date' => date_format($end, 'Y-m-d')

        ];
    }
}
