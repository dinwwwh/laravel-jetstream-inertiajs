<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    public function testUserAccountsCanBeDeleted()
    {
        if (!Features::hasAccountDeletionFeatures()) {
            return static::markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $response = $this->delete('/user', [
            'password' => 'password',
        ]);

        static::assertNull($user->fresh());
    }

    public function testCorrectPasswordMustBeProvidedBeforeAccountCanBeDeleted()
    {
        if (!Features::hasAccountDeletionFeatures()) {
            return static::markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $response = $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        static::assertNotNull($user->fresh());
    }
}
