<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function testRegistrationScreenCanBeRendered()
    {
        if (!Features::enabled(Features::registration())) {
            return static::markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testRegistrationScreenCannotBeRenderedIfSupportIsDisabled()
    {
        if (Features::enabled(Features::registration())) {
            return static::markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function testNewUsersCanRegister()
    {
        if (!Features::enabled(Features::registration())) {
            return static::markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
