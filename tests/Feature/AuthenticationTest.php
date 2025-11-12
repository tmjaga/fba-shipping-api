<?php

use App\Models\User;
use App\Services\FbaService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('Test Token')->plainTextToken;
});

it('Login in with valid credentials', function () {
    $response = $this->postJson(route('login'), [
        'email' => 'test@mail.com',
        'password' => 'password',
    ]);

    expect($response)->assertOk()
        ->and($response['api-token'])
        ->toBeString();
});

it('Login in with invalid credentials', function () {
    $response = $this->postJson(route('login'), [
        'email' => 'invalid_user@mail.com',
        'password' => 'invalid_password',
    ]);

    expect($response->status())->toBe(422);
});

it('Logged user can logs out successfully', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$this->token,
    ])->post(route('logout'));

    expect($response)->assertOk()
        ->and($response->json('message'))
        ->toBe('Logged Out');

    // check tokens
    $tokens = $this->user->tokens()->get();
    expect($tokens)->toHaveCount(0);
});

it("Not logged users can't access protected route", function () {
    $response = $this->postJson(
        route('fulfill-order', ['buyer_id' => 29664, 'order_id' => 16400])
    );

    $response->assertStatus(401);
});

it('Logged users can access protected route', function () {
    $mock = $this->mock(FbaService::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $mock->shouldReceive('makeMockRequest')
        ->once()
        ->andReturn([
            'status' => 'success',
            'tracking_number' => 'FBA-MOCK123456',
            'estimated_delivery' => now()->addDays(2)->toDateString(),
        ]);


    $response = $this->actingAs($this->user, 'sanctum')->postJson(
        route('fulfill-order', ['buyer_id' => 29664, 'order_id' => 16400])
    );

    $response->assertStatus(200);
});
