<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    public function testApiTokensCanBeCreated()
    {
        if (!Features::hasApiFeatures()) {
            return static::markTestSkipped('API support is not enabled.');
        }

        if (Features::hasTeamFeatures()) {
            $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        } else {
            $this->actingAs($user = User::factory()->create());
        }

        $response = $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]);

        static::assertCount(1, $user->fresh()->tokens);
        static::assertSame('Test Token', $user->fresh()->tokens->first()->name);
        static::assertTrue($user->fresh()->tokens->first()->can('read'));
        static::assertFalse($user->fresh()->tokens->first()->can('delete'));
    }
}
