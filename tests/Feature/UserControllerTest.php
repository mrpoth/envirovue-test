<?php

use App\Enums\DetailKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('authenticated users can see the users index', function () {
    $newlyCreatedUsersCount = 10;
    $this->users = User::factory($newlyCreatedUsersCount)->create();

    $response = $this->get((route('users.index')));
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('users/Index')
        ->has('users', $newlyCreatedUsersCount));
});

test('authenticated users can see a single user', function () {

    $response = $this->get((route('users.show', $this->user)));
    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('users/Show')
            ->has(
                'user',
                fn (Assert $page) => $page
                    ->where('email', $this->user->email)
                    ->etc()
            )
    );
});

test('authenticated users can see the edit user page', function () {

    $response = $this->get(route('users.edit', ['user' => $this->user->id]));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('users/Edit')
            ->has(
                'user',
                fn (Assert $page) => $page
                    ->where('id', $this->user->id)
                    ->where('email', $this->user->email)
                    ->etc()
            )
    );
});

test('authenticated users can update a user', function () {

    $newData = [
        'firstname' => 'UpdatedFirstName',
        'lastname' => 'UpdatedLastName',
        'email' => 'updated@example.com',
    ];

    $response = $this->put(route('users.update', ['user' => $this->user->id]), $newData);

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'firstname' => 'UpdatedFirstName',
        'lastname' => 'UpdatedLastName',
        'email' => 'updated@example.com',
    ]);
});

test('authenticated users can soft delete a user', function () {

    $response = $this->delete(route('users.destroy', ['user' => $this->user->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertSoftDeleted('users', ['id' => $this->user->id]);
});

test('authenticated users can see trashed users', function () {
    $this->user = User::factory()->create();
    $trashedUser = User::factory()->create();
    $trashedUser->delete();

    $this->actingAs($this->user);

    $response = $this->get(route('users.trashed'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('users/Trashed')
            ->has(
                'users',
                fn (Assert $page) => $page
                    ->where('0.id', $trashedUser->id)
                    ->etc()
            )
    );
});

test('authenticated users can restore a trashed user', function () {
    $this->user = User::factory()->create();
    $trashedUser = User::factory()->create();
    $trashedUser->delete();

    $this->actingAs($this->user);

    $response = $this->patch(route('users.restore', ['user' => $trashedUser->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $trashedUser->id,
        'deleted_at' => null,
    ]);
});

test('authenticated users can permanently delete a user', function () {

    $response = $this->delete(route('users.delete', ['user' => $this->user->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
});

test('additional user details are saved automatically after user is created or updated', function () {
    $this->user = User::factory()->create();

    $this->assertDatabaseHas('details', [
        'user_id' => $this->user->id,
        'key' => DetailKey::FullName,
        'value' => $this->user->full_name,
    ]);

    $this->user->update(['firstname' => 'New Name']);
    $this->assertDatabaseHas('details', [
        'user_id' => $this->user->id,
        'key' => DetailKey::FullName,
        'value' => $this->user->full_name,
    ]);
});
