<?php

namespace Tests\Feature\Auth;

use App\Helpers\AdminFeatures;
use App\Providers\RouteServiceProvider;
use Tests\AdminTestCase;

class RegistrationTest extends AdminTestCase
{
	public function test_registration_screen_can_be_rendered()
	{
		if (! AdminFeatures::canRegister()) {
			return $this->markTestSkipped('Account registration is not enabled.');
		}

		$response = $this->get('/register');

		$response->assertStatus(200);
	}

	public function test_new_users_can_register()
	{
		if (! AdminFeatures::canRegister()) {
			return $this->markTestSkipped('Account registration is not enabled.');
		}

		$response = $this->post('/register/submit', [
			'name' => 'Test User',
			'email' => 'test@example.com',
			'password' => 'Password123?',
			'password_confirmation' => 'Password123?',
		]);

		$this->assertAuthenticated($this->guard);
		$response->assertRedirect(RouteServiceProvider::ADMIN);
	}
}
