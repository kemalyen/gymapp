<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Plan;
use App\Models\Membership;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();
        Role::truncate();
        Plan::truncate();
        Profile::truncate();
        Membership::truncate();
        Role::create(['name' => 'member']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'trainer']);
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'sales']);
        Role::create(['name' => 'trial']);
        Plan::factory(5)->create();

        User::factory(100)->member()
            ->has(Membership::factory())
            ->create();

        User::factory(10)->trial()
            ->has(Membership::factory())
            ->create();            


        User::factory(5)->trainer()->create();
        User::factory(8)->staff()->create();
        User::factory(10)->user()->create();
        User::factory()->admin()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


    }
}
