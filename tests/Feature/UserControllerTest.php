<?php

use App\Enums\DetailKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('authenticated users can see the users index', function () {
    $user = User::factory()->create();
    $users = User::factory(10)->create();
    $this->actingAs($user);

    $response = $this->get((route('users.index')));
    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page
        ->component('users/Index')
        ->has('users', count(User::all())));
});

test('authenticated users can see a single user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get((route('users.show', $user)));
    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('users/Show')
            ->has(
                'user',
                fn(Assert $page) => $page
                    ->where('email', $user->email)
                    ->etc()
            )
    );
});

test('authenticated users can see the edit user page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('users.edit', ['user' => $user->id]));

    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('users/Edit')
            ->has(
                'user',
                fn(Assert $page) => $page
                    ->where('id', $user->id)
                    ->where('email', $user->email)
                    ->etc()
            )
    );
});

test('authenticated users can update a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newData = [
        'firstname' => 'UpdatedFirstName',
        'lastname' => 'UpdatedLastName',
        'email' => 'updated@example.com',
    ];

    $response = $this->put(route('users.update', ['user' => $user->id]), $newData);

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'firstname' => 'UpdatedFirstName',
        'lastname' => 'UpdatedLastName',
        'email' => 'updated@example.com',
    ]);
});

test('authenticated users can soft delete a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->delete(route('users.destroy', ['user' => $user->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertSoftDeleted('users', ['id' => $user->id]);
});

test('authenticated users can see trashed users', function () {
    $user = User::factory()->create();
    $trashedUser = User::factory()->create();
    $trashedUser->delete();

    $this->actingAs($user);

    $response = $this->get(route('users.trashed'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('users/Trashed')
            ->has(
                'users',
                fn(Assert $page) => $page
                    ->where('0.id', $trashedUser->id)
                    ->etc()
            )
    );
});

test('authenticated users can restore a trashed user', function () {
    $user = User::factory()->create();
    $trashedUser = User::factory()->create();
    $trashedUser->delete();

    $this->actingAs($user);

    $response = $this->patch(route('users.restore', ['user' => $trashedUser->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $trashedUser->id,
        'deleted_at' => null,
    ]);
});

test('authenticated users can permanently delete a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->delete(route('users.delete', ['user' => $user->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('additional user details are saved automatically after user is created or updated', function () {
    $user = User::factory()->create();

    $this->assertDatabaseHas('details', [
        'user_id' => $user->id,
        'key' => DetailKey::FullName,
        'value' => $user->full_name
    ]);

    $user->update(['firstname' => 'New Name']);
    $this->assertDatabaseHas('details', [
        'user_id' => $user->id,
        'key' => DetailKey::FullName,
        'value' => $user->full_name
    ]);
});
