<?php

use App\Livewire\TaskList;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('can render task list component', function () {
    $user = User::factory()->create();
    Auth::login($user);

    Livewire::test(TaskList::class)
        ->assertOk();
});

test('can create task via Livewire component', function () {
    $user = User::factory()->create();
    Auth::login($user);

    Livewire::test(TaskList::class)
        ->set('form.title', 'Entrevista')
        ->call('createTask')
        ->assertHasNoErrors()
        ->assertDispatched('taskCreated');

    expect(Task::where('title', 'Entrevista')->exists())->toBeTrue();
});
