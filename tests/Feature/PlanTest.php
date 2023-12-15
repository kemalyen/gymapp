<?php

use App\Filament\Resources\PlanResource;
use App\Models\Plan;

use function Pest\Livewire\livewire;
 
it('can create', function () {
    $newData = Plan::factory()->create();
 
    livewire(PlanResource\Pages\CreatePlan::class)
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
 
    livewire(PlanResource\Pages\EditPlan::class, [
        'record' => $new_post->getRouteKey(),
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