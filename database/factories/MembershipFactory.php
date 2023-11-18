<?php

namespace Database\Factories;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
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
            'contact_id' => Contract::all()->random()->id,
            'status' => 1,
            'start_date' => date_format($start, 'Y-m-d'),
            'end_date' => date_format( $end, 'Y-m-d')

        ];
    }
}
