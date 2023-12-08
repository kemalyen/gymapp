<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\Profile;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            return $user->profile()->save(Profile::factory()->create(['user_id' => $user->id]));
        })->afterCreating(function (User $user) {
            return $user->memberships()->saveMany(
                Membership::factory()->count(random_int(1, 5))
                    ->create(
                        [
                            'user_id' => $user->id,
                            'status' => 0,
                            'start_date' => Carbon::today()->subDays(rand(10000, 365)),
                            'end_date' => Carbon::today()->subDays(rand(10000, 365))
                        ]
                    )
            );
        });
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function member(): UserFactory
    {
        return $this->assignRole('member');
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function trial(): UserFactory
    {
        return $this->assignRole('trial');
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function admin(): UserFactory
    {
        return $this->assignRole('admin');
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function staff(): UserFactory
    {
        return $this->assignRole('staff');
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function trainer(): UserFactory
    {
        return $this->assignRole('trainer');
    }

        /**
     * Indicate that the user is an admin.
     *
     * @return Factory
     */
    public function user(): UserFactory
    {
        return $this->assignRole('user');
    }

    /**
     * @param array|\Spatie\Permission\Contracts\Role|string  ...$roles
     * @return UserFactory
     */
    private function assignRole(...$roles): UserFactory
    {
        return $this->afterCreating(fn (User $user) => $user->syncRoles($roles));
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName() . ' '. $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password 
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
