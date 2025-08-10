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
    $users = User::factory($newlyCreatedUsersCount)->create();

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

test('guest cannot access users index', function () {
    auth()->logout();
    $response = $this->get(route('users.index'));
    $response->assertRedirect(route('login')); // or 302 redirect to login
});

test('guest cannot view single user', function () {
    auth()->logout();
    $response = $this->get(route('users.show', $this->user));
    $response->assertRedirect(route('login'));
});

test('authenticated users can create a single user', function () {
    $userData = [
        'prefixname' => 'Mr',
        'firstname' => 'Test',
        'middlename' => 'Tester',
        'lastname' => 'Testering',
        'email' => 'test@test.com',
        'email_verified_at' => now(),
        'password' => 'tester123',
    ];

    $response = $this->post(route('users.store'), $userData);

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'prefixname' => 'Mr',
        'firstname' => 'Test',
        'middlename' => 'Tester',
        'lastname' => 'Testering',
        'email' => 'test@test.com',
    ]);
});

test('user creation fails with missing required fields', function () {
    $response = $this->post(route('users.store'), []);
    $response->assertSessionHasErrors(['firstname', 'lastname', 'email', 'password']);
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

test('user update fails with missing data', function () {
    $response = $this->put(route('users.update', $this->user), [
        'firstname' => 'UpdatedFirstName',
    ]);
    $response->assertSessionHasErrors();
});

test('authenticated users can soft delete a user', function () {

    $response = $this->delete(route('users.destroy', ['user' => $this->user->id]));

    $response->assertStatus(302);
    $response->assertRedirect();

    $this->assertSoftDeleted('users', ['id' => $this->user->id]);
});

test('authenticated users can see trashed users', function () {
    $trashedUser = User::factory()->create();
    $trashedUser->delete();

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
    $trashedUser = User::factory()->trashed()->create();

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

    $userData = [
        'prefixname' => 'Mr',
        'firstname' => 'Test',
        'middlename' => 'Tester',
        'lastname' => 'Testering',
        'email' => 'test@test.com',
        'email_verified_at' => now(),
        'password' => 'tester123',
    ];

    $response = $this->post(route('users.store'), $userData);
    $response->assertStatus(302);

    $user = User::where('email', 'test@test.com')->first();

    $this->assertDatabaseHas('details', [
        'user_id' => $user->id,
        'key' => DetailKey::FullName,
        'value' => $user->full_name,
    ]);

    $user->update(['firstname' => 'New Name']);
    $this->assertDatabaseHas('details', [
        'user_id' => $user->id,
        'key' => DetailKey::FullName,
        'value' => $user->full_name,
    ]);
});
