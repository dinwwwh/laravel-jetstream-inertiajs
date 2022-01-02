<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    public function testApiTokenPermissionsCanBeUpdated()
    {
        if (!Features::hasApiFeatures()) {
            return static::markTestSkipped('API support is not enabled.');
        }

        if (Features::hasTeamFeatures()) {
            $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        } else {
            $this->actingAs($user = User::factory()->create());
        }

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->put('/user/api-tokens/'.$token->id, [
            'name' => $token->name,
            'permissions' => [
                'delete',
                'missing-permission',
            ],
        ]);

        static::assertTrue($user->fresh()->tokens->first()->can('delete'));
        static::assertFalse($user->fresh()->tokens->first()->can('read'));
        static::assertFalse($user->fresh()->tokens->first()->can('missing-permission'));
    }
}
