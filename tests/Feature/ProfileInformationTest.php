<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    public function testProfileInformationCanBeUpdated(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        static::assertSame('Test Name', $user->fresh()->name);
        static::assertSame('test@example.com', $user->fresh()->email);
    }
}
