<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BrowserSessionsTest extends TestCase
{
    public function testOtherBrowserSessionsCanBeLoggedOut(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->delete('/user/other-browser-sessions', [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
