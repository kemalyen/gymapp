<?php

use App\Filament\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;

//use function Pest\Livewire\livewire;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed();
    $this->adminUser = User::whereEmail('test@example.com')->first();
    actingAs($this->adminUser);
});

it('can create', function () {
    $newData = Plan::factory()->create();
 
    Livewire::test(PlanResource\Pages\CreatePlan::class)
        ->fillForm([
            'name' => $newData->name,
            'price' => $newData->price,
            'period' => $newData->period,
            'description' => $newData->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Plan::class, [
        'name' => $newData->name,
        'price' => $newData->price,
        'period' => $newData->period,
        'description' => $newData->description,
    ]);
});

it('can save', function () {
    $plan = Plan::factory()->create();
    $new_post = Plan::factory()->create();
 
    Livewire::test(PlanResource\Pages\EditPlan::class, [
        'record' => $plan->getRouteKey(),
    ])
        ->fillForm([
            'name' => $new_post->name,
            'price' => $new_post->price,
            'description' => $new_post->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($plan->refresh())
        ->description->toBe($new_post->description)
        ->price->toBe($new_post->price)
        ->name->toBe($new_post->name);
});