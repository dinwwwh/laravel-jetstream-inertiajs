<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    public function testPasswordCanBeUpdated(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        static::assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function testCurrentPasswordMustBeCorrect(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors();

        static::assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function testNewPasswordsMustMatch(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();

        static::assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
