<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'prefixname' => 'Mr', // Optional
        'firstname' => 'Test', // Required
        'middlename' => 'Middle', // Optional
        'lastname' => 'User', // Required
        'email' => 'test@example.com', // Required
        'password' => 'password123', // Required, must be at least 8 characters
        'password_confirmation' => 'password123', // Required for confirmation
        'remember_token' => null, // Optional
        'email_verified_at' => null, // Optional
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
