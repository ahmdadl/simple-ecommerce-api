<?php

use Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    actingAs($this->user, 'customer'); // uses the 'auth:customer' guard
});

it('updates user profile successfully', function () {
    $data = [
        'name' => 'New Name',
        'email' => 'newemail@example.com',
       'phone' => fakePhone()
    ];

    $response = patchJson(route('api.profile.updateProfile'), $data);

    $response->assertOk();

    $this->user->refresh();

    expect($this->user->name)->toBe($data['name']);
    expect($this->user->email)->toBe($data['email']);
});

it('changes user password successfully', function () {
    $data = [
        'old_password' => 'old-password',
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ];

    $response = patchJson(route('api.profile.changePassword'), $data);

    $response->assertNoContent();

    $this->user->refresh();

    expect(Hash::check('new-secret-password', $this->user->password))->toBeTrue();
});

it('fails to update profile with invalid data', function () {
    $response = patchJson(route('api.profile.updateProfile'), [
        'email' => 'not-an-email',
    ]);

    $response->assertStatus(422);
});

it('fails to change password with wrong current password', function () {
    $response = patchJson(route('api.profile.changePassword'), [
        'old_password' => 'wrong-password',
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ]);

    $response->assertStatus(422);
});
